<script>
document.addEventListener("DOMContentLoaded", () => {
    // Load default news filter
    loadRecentNews("Today");

    // Set initial filter text and load announcements
    document.getElementById('filter-text').textContent = `| Today`; // Set default filter text
    loadRecentAnns("Today"); // Load with "Today" as default
    console.log("DOM Loaded, calling loadRecentAnns with Today");
});

// Handle filters for Recent News
document.querySelectorAll('.card .filter .dropdown-item[data-filter]').forEach(item => {
    item.addEventListener('click', function(event) {
        event.preventDefault();
        const selectedFilter = this.getAttribute('data-filter');
        const parentCard = this.closest('.card'); // Find the specific card container

        if (parentCard.classList.contains('website-visits-card')) {
            loadWebsiteVisits(selectedFilter);
        } else if (parentCard.classList.contains('news-card')) {
            loadRecentNews(selectedFilter);
        }
    });
});

// Handle filters for Recent Announcements
document.querySelectorAll('.revenue-card .filter .dropdown-item[data-filter]').forEach(item => {
    item.addEventListener('click', function(event) {
        event.preventDefault();
        const selectedFilter = this.getAttribute('data-filter');
        document.getElementById('filter-text').textContent = `| ${selectedFilter}`; // Update filter text
        loadRecentAnns(selectedFilter); // Load with the new filter
        console.log(`Filter changed to: ${selectedFilter}, calling loadRecentAnns`);
    });
});

// Load Recent News
function loadRecentNews(filter = "Today") {
    document.getElementById('news-filter').textContent = `| ${filter}`;
    const newsActivity = document.getElementById('news-activity');
    newsActivity.innerHTML = '';

    fetch(`getRecentNews?filter=${filter}`)
        .then(response => response.json())
        .then(newsItems => {
            if (newsItems.length === 0) {
                newsActivity.innerHTML = "<p>No recent news added.</p>";
                return;
            }

            newsItems.forEach(news => {
                const formattedDate = new Date(news.created_date).toLocaleDateString();
                const newsElement = `
                    <div class="activity-item d-flex">
                        <div class="activity-label">${formattedDate}</div>
                        <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                        <div class="activity-content">
                            <span class="fw-bold text-dark">${news.title}</span>
                        </div>
                    </div>
                `;
                newsActivity.insertAdjacentHTML('beforeend', newsElement);
            });
        })
        .catch(error => console.error('Error fetching news:', error));
}

// Load Recent Announcements
function loadRecentAnns(filter = "Today") {
    const announcementTitle = document.getElementById('announcement-title');
    const contentCount = document.getElementById('content-count');
    const updateDate = document.getElementById('update-date');

    if (!announcementTitle || !contentCount || !updateDate) {
        console.error('One or more DOM elements not found:', { announcementTitle, contentCount, updateDate });
        return;
    }

    // Show loading state
    announcementTitle.textContent = 'Loading...';
    contentCount.textContent = '';
    updateDate.textContent = '';

    console.log(`Fetching announcements with filter: ${filter}, URL: <?= base_url('admin/getRecentAnns') ?>?filter=${filter}`);
    fetch(`<?= base_url('admin/getRecentAnns') ?>?filter=${filter}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
            // Uncomment if CSRF is enabled: 'X-CSRF-TOKEN': '<?= csrf_token() ?>'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(announcements => {
        console.log('Fetched announcements:', announcements);
        if (announcements.length === 0) {
            announcementTitle.textContent = 'No announcements';
            contentCount.textContent = '0';
            updateDate.textContent = 'No updates';
            return;
        }

        // Use the most recent announcement
        const latestAnn = announcements[0];
        announcementTitle.textContent = latestAnn.title || 'No title';
        contentCount.textContent = announcements.length; // Number of announcements
        updateDate.textContent = `Updated on ${new Date(latestAnn.created_date).toLocaleDateString()}`;
    })
    .catch(error => {
        console.error('Error fetching announcements:', error.message, 'Full error:', error);
        announcementTitle.textContent = 'Error loading';
        contentCount.textContent = '0';
        updateDate.textContent = 'No updates';
    });
}

//Handle websites visit home count
function loadWebsiteVisits(filter = "Today") {
    document.getElementById('visits-filter-text').textContent = `| ${filter}`;
    const visitCountElement = document.getElementById('visit-count');
    visitCountElement.textContent = 'Loading...';

    fetch(`getVisitCount?filter=${filter}`)
        .then(response => response.json())
        .then(data => {
            console.log("API Response:", data); // Debugging step
            visitCountElement.textContent = data.visit_count ?? "Error";
        })
        .catch(error => {
            console.error('Error fetching visit count:', error);
            visitCountElement.textContent = 'Error';
        });
}

// Handle filter clicks for Website Visits only
document.querySelectorAll('#website-visits-card .dropdown-item[data-filter]').forEach(item => {
    item.addEventListener('click', function(event) {
        event.preventDefault();
        const selectedFilter = this.getAttribute('data-filter');
        loadWebsiteVisits(selectedFilter);
    });
});

// Handle Reports - Most Visited Website
document.addEventListener("DOMContentLoaded", () => {
    // Hypothetical visit data (replace with real data from your analytics/database)
    const visitData = {
        today: {
            categories: ['Home', 'About', 'Transparency', 'Careers', 'Invest', 'Contact'],
            data: [50, 30, 40, 10, 5, 3] // Visits as of May 30, 2025, 04:06 PM PST
        },
        month: {
            categories: ['Home', 'About', 'Transparency', 'Careers', 'Invest', 'Contact'],
            data: [1200, 850, 950, 300, 200, 150] // Visits for May 2025
        },
        year: {
            categories: ['Home', 'About', 'Transparency', 'Careers', 'Invest', 'Contact'],
            data: [5000, 3500, 4000, 1200, 800, 600] // Visits for 2025
        }
    };

    let currentFilter = 'month'; // Default filter (This Month)

    // Handle filter selection
    document.querySelectorAll('#filterDropdown .dropdown-item').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            currentFilter = item.getAttribute('data-filter');
            document.getElementById('filterSpan').textContent = '/' + item.textContent;

            // Update chart with new data
            chart.updateOptions({
                series: [{
                    name: 'Visits',
                    data: visitData[currentFilter].data
                }],
                xaxis: {
                    categories: visitData[currentFilter].categories
                }
            });
        });
    });

    // Optional: Fetch real data via AJAX (uncomment and adjust if using a backend)
    
function fetchVisitData(filter) {
        fetch(`<?= base_url('admin/getVisitData') ?>?filter=${filter}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            chart.updateOptions({
                series: [{
                    name: 'Visits',
                    data: data.data
                }],
                xaxis: {
                    categories: data.categories
                }
            });
            document.getElementById('filterSpan').textContent = '/' + filter.charAt(0).toUpperCase() + filter.slice(1);
        })
        .catch(error => console.error('Error fetching visit data:', error));
    }

    // Initial load and filter event with AJAX
    document.querySelectorAll('#filterDropdown .dropdown-item').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const filter = item.getAttribute('data-filter');
            fetchVisitData(filter);
        });
    });
    fetchVisitData('month'); // Initial load
});
</script>