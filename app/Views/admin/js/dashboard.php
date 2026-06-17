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
    const allChartLabels = [
        'Home', 'Mission & Vision', 'City Officials', 'History', 'Barangays', 'Jobs',
        'Invest', 'Contact', 'Departments', 'Maps', 'Full Disclosure Policy', 'Careers'
    ];

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

    // Setup Area Chart
    const ctxArea = document.getElementById("myAreaChart");
    let myLineChart;
    
    function updateAreaChart() {
        const counts = allChartLabels.map(label => {
            const index = findLabelMatch(label);
            return index !== -1 ? cleanCounts[index] : 0;
        });

        if (!myLineChart) {
            myLineChart = new Chart(ctxArea, {
                type: 'line',
                data: {
                    labels: allChartLabels,
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
                        data: counts,
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
            myLineChart.data.labels = allChartLabels;
            myLineChart.data.datasets[0].data = counts;
            myLineChart.update();
        }
    }
    updateAreaChart();

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
        const newsFilter = document.getElementById('news-filter');
        if (newsFilter) newsFilter.textContent = `| ${filter}`;
        const newsFeedFilter = document.getElementById('news-feed-filter');
        if (newsFeedFilter) newsFeedFilter.textContent = `| ${filter}`;
        
        const newsCount = document.getElementById('news-count');
        const newsFeedCount = document.getElementById('news-feed-count');
        
        fetch(`<?= base_url('admin/getRecentNews') ?>?filter=${filter}`)
            .then(res => res.json())
            .then(data => {
                if (newsCount) newsCount.textContent = data.length;
                if (newsFeedCount) newsFeedCount.textContent = data.length;
            })
            .catch(() => {
                if (newsCount) newsCount.textContent = '0';
                if (newsFeedCount) newsFeedCount.textContent = '0';
            });
    }

    document.querySelectorAll('.news-card .dropdown-item[data-filter], .news-feed-card .dropdown-item[data-filter]').forEach(item => {
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