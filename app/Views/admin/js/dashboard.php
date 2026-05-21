<script>
document.addEventListener("DOMContentLoaded", () => {
    // Basic setups
    document.getElementById('filter-text').textContent = `| Today`;
    loadRecentAnns("Today");
    loadRecentNews("Today");
    
    // Line Chart Data
    const allLabels = <?php echo json_encode($visit_labels ?? []); ?>;
    const allCounts = <?php echo json_encode($visit_counts ?? [], JSON_NUMERIC_CHECK); ?>;
    const cleanCounts = allCounts.map(count => parseInt(count) || 0);
    
    // Group definitions
    const page1Labels = ['Home', 'Mission & Vision', 'City Officials', 'History', 'Barangays', 'Jobs'];
    const page2Labels = ['Invest', 'Contact', 'Departments', 'Maps', 'Full Disclosure Policy', 'Careers'];
    let currentPage = 1;

    // Helper to find index in PHP data
    function findLabelMatch(targetLabel) {
        let index = allLabels.indexOf(targetLabel);
        if (index !== -1) return index;
        index = allLabels.findIndex(phpLabel => phpLabel.toLowerCase().trim() === targetLabel.toLowerCase().trim());
        if (index !== -1) return index;
        return allLabels.findIndex(phpLabel => 
            phpLabel.toLowerCase().includes(targetLabel.toLowerCase()) || targetLabel.toLowerCase().includes(phpLabel.toLowerCase())
        );
    }

    // Colors for Pie Chart
    const pieColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];
    const pieHoverColors = ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617', '#60616f'];

    // Setup Area Chart
    const ctxArea = document.getElementById("myAreaChart");
    let myLineChart;
    
    function updateAreaChart() {
        const paginatedLabels = currentPage === 1 ? page1Labels : page2Labels;
        const paginatedCounts = paginatedLabels.map(label => {
            const index = findLabelMatch(label);
            return index !== -1 ? cleanCounts[index] : 0;
        });

        if (!myLineChart) {
            myLineChart = new Chart(ctxArea, {
                type: 'line',
                data: {
                    labels: paginatedLabels,
                    datasets: [{
                        label: "Visits",
                        tension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: paginatedCounts,
                        fill: true
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                    scales: {
                        x: { grid: { display: false, drawBorder: false } },
                        y: { 
                            beginAtZero: true,
                            ticks: { maxTicksLimit: 5, padding: 10 },
                            grid: { color: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2] }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: "rgb(255,255,255)", titleColor: '#6e707e', bodyColor: "#858796", borderColor: '#dddfeb', borderWidth: 1, padding: 15, displayColors: false, intersect: false, mode: 'index' }
                    }
                }
            });
        } else {
            myLineChart.data.labels = paginatedLabels;
            myLineChart.data.datasets[0].data = paginatedCounts;
            myLineChart.update();
        }

        // Update pagination buttons
        document.getElementById('page1Btn').className = currentPage === 1 ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-primary';
        document.getElementById('page2Btn').className = currentPage === 2 ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-primary';
    }
    updateAreaChart();

    document.getElementById('page1Btn').addEventListener('click', () => { if (currentPage !== 1) { currentPage = 1; updateAreaChart(); } });
    document.getElementById('page2Btn').addEventListener('click', () => { if (currentPage !== 2) { currentPage = 2; updateAreaChart(); } });

    // Setup Pie Chart
    const ctxPie = document.getElementById("myPieChart");
    
    // Get Top 3 pages
    let combinedData = allLabels.map((lbl, idx) => ({ label: lbl, count: cleanCounts[idx] }));
    combinedData.sort((a, b) => b.count - a.count);
    const top3Data = combinedData.slice(0, 3);
    const pieLabels = top3Data.map(d => d.label);
    const pieCounts = top3Data.map(d => d.count);

    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieCounts,
                backgroundColor: pieColors.slice(0, pieCounts.length),
                hoverBackgroundColor: pieHoverColors.slice(0, pieCounts.length),
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                tooltip: { backgroundColor: "rgb(255,255,255)", bodyColor: "#858796", borderColor: '#dddfeb', borderWidth: 1, padding: 15, displayColors: false },
                legend: { display: false }
            },
            cutout: '80%'
        },
    });

    // Populate Pie Legend
    const legendContainer = document.getElementById("pie-chart-legend");
    let legendHtml = '';
    pieLabels.forEach((label, idx) => {
        legendHtml += `<span class="mr-2"><i class="fas fa-circle" style="color:${pieColors[idx]}"></i> ${label}</span>`;
    });
    legendContainer.innerHTML = legendHtml;

    // Load Announcements
    function loadRecentAnns(filter) {
        document.getElementById('filter-text').textContent = `| ${filter}`;
        const countEl = document.getElementById('content-count');
        
        fetch(`<?= base_url('admin/getRecentAnns') ?>?filter=${filter}`)
            .then(res => res.json())
            .then(data => { countEl.textContent = data.length; })
            .catch(() => { countEl.textContent = '0'; });
    }

    document.querySelectorAll('.revenue-card .dropdown-item[data-filter]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            loadRecentAnns(this.getAttribute('data-filter'));
        });
    });

    // Load Recent News
    function loadRecentNews(filter) {
        document.getElementById('news-filter').textContent = `| ${filter}`;
        const newsActivity = document.getElementById('news-activity');
        const newsCount = document.getElementById('news-count');
        newsActivity.innerHTML = 'Loading...';
        
        fetch(`<?= base_url('admin/getRecentNews') ?>?filter=${filter}`)
            .then(res => res.json())
            .then(data => {
                newsCount.textContent = data.length;
                if (data.length === 0) {
                    newsActivity.innerHTML = "<p class='text-muted'>No recent news added.</p>";
                    return;
                }
                newsActivity.innerHTML = '';
                data.forEach(news => {
                    const d = new Date(news.created_date).toLocaleDateString();
                    newsActivity.insertAdjacentHTML('beforeend', `
                        <div class="mb-2 pb-2 border-bottom text-sm">
                            <span class="font-weight-bold text-dark d-block">${news.title}</span>
                            <span class="text-xs text-muted"><i class="fas fa-calendar-alt"></i> ${d}</span>
                        </div>
                    `);
                });
            })
            .catch(() => { newsActivity.innerHTML = 'Error loading.'; newsCount.textContent = '0'; });
    }

    document.querySelectorAll('.news-card .dropdown-item[data-filter]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            loadRecentNews(this.getAttribute('data-filter'));
        });
    });

    // Website Visits filters
    function loadWebsiteVisits(filter) {
        document.getElementById('visits-filter-text').textContent = `| ${filter}`;
        const visitCountElement = document.getElementById('visit-count');
        visitCountElement.textContent = 'Loading...';

        fetch(`<?= base_url('admin/getVisitCount') ?>?filter=${filter}`)
            .then(res => res.json())
            .then(data => { visitCountElement.textContent = data.visit_count ?? "0"; })
            .catch(() => { visitCountElement.textContent = 'Error'; });
    }

    document.querySelectorAll('.website-visits-card .dropdown-item[data-filter]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            loadWebsiteVisits(this.getAttribute('data-filter'));
        });
    });
});
</script>