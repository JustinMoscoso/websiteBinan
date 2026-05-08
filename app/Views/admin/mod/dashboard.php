<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
  <div class="row">

    <!-- Left side columns -->
    <div class="col-lg-8">
      <div class="row">

       <!-- Website Visits Card -->
<div class="col-xxl-6 col-md-6">
    <div class="card info-card website-visits-card" id="website-visits-card"> <!-- Ensure ID matches -->
        <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-three-dots"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                </li>
                <li><a class="dropdown-item" href="#" data-filter="Today">Today</a></li>
                <li><a class="dropdown-item" href="#" data-filter="This Month">This Month</a></li>
                <li><a class="dropdown-item" href="#" data-filter="This Year">This Year</a></li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Website Visited <span id="visits-filter-text">| Today</span></h5> <!-- FIXED ID -->
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-globe"></i>
                </div>
                <div class="ps-3">
                    <h6 id="visit-count"><?php echo isset($visit_count) ? $visit_count : 0; ?></h6>
                    <span class="text-success small pt-1 fw-bold">visits</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Card -->
<div class="col-xxl-6 col-md-6">
  <div class="card info-card revenue-card">
    <div class="filter">
      <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <li class="dropdown-header text-start">
          <h6>Filter</h6>
        </li>
        <li><a class="dropdown-item" href="#" data-filter="Today">Today</a></li>
        <li><a class="dropdown-item" href="#" data-filter="This Month">This Month</a></li>
        <li><a class="dropdown-item" href="#" data-filter="This Year">This Year</a></li>
      </ul>
    </div>

    <div class="card-body">
      <h5 class="card-title">Recent announcements <span id="filter-text">| Today</span></h5>

      <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
          <i class="bi bi-file-earmark-text"></i>
        </div>
        <div class="ps-3">
          <h6 id="announcement-title">Announcements</h6>
          <span class="text-success small pt-1 fw-bold" id="content-count">2</span>
          <span class="text-muted small pt-2 ps-1" id="update-date">Updated on [Date]</span>
        </div>
      </div>
       <a href="http://localhost/websitebinan/public/announcements/1" class="news-link">Announcements <i class="bi bi-box-arrow-up-right"></i></a>
    </div>
  </div>
</div><!-- End Revenue Card -->

<!-- Reports -->
<div class="col-12">
  <div class="card">
    <div class="filter">
      <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <li class="dropdown-header text-start">
          <h6>Filter</h6>
        </li>
        <li><a class="dropdown-item" href="<?= base_url('admin/dashboard?filter=today') ?>">Today</a></li>
        <li><a class="dropdown-item" href="<?= base_url('admin/dashboard?filter=month') ?>">This Month</a></li>
        <li><a class="dropdown-item" href="<?= base_url('admin/dashboard?filter=year') ?>">This Year</a></li>
      </ul>
    </div>
    <div class="card-body">
      <h5 class="card-title">Page Views <span id="filter-text">/<?php echo ucfirst($filter ?? 'Today'); ?></span></h5>
      <!-- Bar Chart -->
      <div id="pageVisitsChart"></div>
      <!-- Pagination Controls -->
      <div class="pagination-controls" style="display: flex; justify-content: flex-end; align-items: center; margin-top: 20px; gap: 5px;">
        <button id="page1Btn" class="btn btn-sm btn-primary" style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">1</button>
        <button id="page2Btn" class="btn btn-sm btn-outline-primary" style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">2</button>
      </div>
      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const allLabels = <?php echo json_encode($visit_labels ?? []); ?>;
          const allCounts = <?php echo json_encode($visit_counts ?? [], JSON_NUMERIC_CHECK); ?>;
          
          // Force all counts to be integers
          const cleanCounts = allCounts.map(count => parseInt(count) || 0);
          
          // Debug: Log the PHP data and their types
          console.log('PHP Labels:', allLabels);
          console.log('Original PHP Counts:', allCounts);
          console.log('Cleaned PHP Counts:', cleanCounts);
          console.log('PHP Counts types:', cleanCounts.map(count => typeof count + ': ' + count));
          
          // Define the two groups explicitly
          const page1Labels = ['Home', 'Mission & Vision', 'City Officials', 'History', 'Barangays', 'Jobs'];
          const page2Labels = ['Invest', 'Contact', 'Departments', 'Maps', 'Full Disclosure Policy', 'Careers'];

          let currentPage = 1;
          let chartInstance = null; // To store the chart instance

          // Helper function to find matching label with flexible matching
          function findLabelMatch(targetLabel) {
            // First try exact match
            let index = allLabels.indexOf(targetLabel);
            if (index !== -1) return index;
            
            // Try case-insensitive and trimmed match
            index = allLabels.findIndex(phpLabel => 
              phpLabel.toLowerCase().trim() === targetLabel.toLowerCase().trim()
            );
            if (index !== -1) return index;
            
            // Try partial match (contains)
            index = allLabels.findIndex(phpLabel => 
              phpLabel.toLowerCase().includes(targetLabel.toLowerCase()) ||
              targetLabel.toLowerCase().includes(phpLabel.toLowerCase())
            );
            
            return index;
          }

          function updateChart() {
            let paginatedLabels, paginatedCounts;

            console.log('Updating chart for page:', currentPage);

            if (currentPage === 1) {
              paginatedLabels = page1Labels;
            } else if (currentPage === 2) {
              paginatedLabels = page2Labels;
            }

            paginatedCounts = paginatedLabels.map(label => {
              const index = findLabelMatch(label);
              const count = index !== -1 ? cleanCounts[index] : 0;
              
              // Convert to integer to avoid decimal display
              const intCount = parseInt(count) || 0;
              
              console.log(`Label: "${label}", Matched Index: ${index}, Count: ${count} -> ${intCount}`);
              
              // Additional debug: show what PHP label was matched
              if (index !== -1) {
                console.log(`  -> Matched with PHP label: "${allLabels[index]}"`);
              } else {
                console.log(`  -> No match found for "${label}"`);
              }
              
              return intCount;
            });

            console.log('Final paginated counts:', paginatedCounts);

            // If chart doesn't exist, create it
            if (!chartInstance) {
              chartInstance = new ApexCharts(document.querySelector("#pageVisitsChart"), {
                series: [{
                  name: 'Visits',
                  data: paginatedCounts
                }],
                chart: {
                  height: 350,
                  type: 'bar',
                  toolbar: {
                    show: false
                  },
                },
                plotOptions: {
                  bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                  },
                },
                colors: ['#4154f1', '#2eca6a', '#ff771d', '#ff5733', '#33ff57'],
                dataLabels: {
                  enabled: false
                },
                xaxis: {
                  categories: paginatedLabels,
                  title: {
                    text: 'Pages'
                  }
                },
                yaxis: {
                  title: {
                    text: 'Number of Views' // Ensure this label is present
                  },
                  labels: {
                    formatter: function (value) {
                      return parseInt(value); // Remove decimal places
                    }
                  }
                },
                tooltip: {
                  y: {
                    formatter: function (val) {
                      return parseInt(val) + ' visits'; // Ensure tooltip shows integer
                    }
                  }
                }
              });
              chartInstance.render();
            } else {
              // Update existing chart with new data and categories
              chartInstance.updateOptions({
                series: [{
                  name: 'Visits',
                  data: paginatedCounts
                }],
                xaxis: {
                  categories: paginatedLabels,
                  title: {
                    text: 'Pages'
                  }
                },
                yaxis: {
                  title: {
                    text: 'Number of Visits' // Ensure this label is preserved on update
                  },
                  labels: {
                    formatter: function (value) {
                      return parseInt(value); // Remove decimal places on update
                    }
                  }
                }
              });
            }

            // Update pagination buttons
            document.getElementById('page1Btn').className = currentPage === 1 ? 
              'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-primary';
            document.getElementById('page2Btn').className = currentPage === 2 ? 
              'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-primary';
          }

          updateChart();

          document.getElementById('page1Btn').addEventListener('click', () => {
            console.log('Page 1 button clicked, current page:', currentPage);
            if (currentPage !== 1) {
              currentPage = 1;
              console.log('Moving to page:', currentPage);
              updateChart();
            }
          });

          document.getElementById('page2Btn').addEventListener('click', () => {
            console.log('Page 2 button clicked, current page:', currentPage);
            if (currentPage !== 2) {
              currentPage = 2;
              console.log('Moving to page:', currentPage);
              updateChart();
            }
          });
        });
      </script>
      <!-- End Bar Chart -->
    </div>
  </div>
</div><!-- End Reports -->
      </div>
    </div><!-- End Left side columns -->

   <!-- Right side columns -->
<style>
    .news-link {
        color: #0000FF;
        font-size: 14px;
    }
    .news-link i {
        color: #0000FF;
        font-size: 14px;
        vertical-align: middle;
    }
    .dashboard-card {
        margin: 15px 0;
        padding: 15px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-align: center;
    }
    .dashboard-card h5 {
        margin-bottom: 10px;
        color: #6c757d;
    }
    .dashboard-card .value {
        font-size: 24px;
        color: #000;
    }
    .dashboard-card .subtext {
        color: #28a745;
        font-size: 14px;
    }
    .highlight-card {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .highlight-card .nav-tabs {
        border-bottom: none;
    }
    .highlight-card .nav-tabs .nav-link {
        background-color: #343a40;
        color: #fff;
        border: none;
        padding: 10px 20px;
        margin-right: 5px;
        border-radius: 5px 5px 0 0;
    }
    .highlight-card .nav-tabs .nav-link.active {
        background-color: #fff;
        color: #000;
    }
    .highlight-card .list-group-item {
        border: none;
        padding: 5px 0;
    }
</style>
<div class="col-lg-4">
    <div class="card news-card">
        <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-three-dots"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                </li>
                <li><a class="dropdown-item" href="#" data-filter="Today">Today</a></li>
                <li><a class="dropdown-item" href="#" data-filter="This Month">This Month</a></li>
                <li><a class="dropdown-item" href="#" data-filter="This Year">This Year</a></li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Recent News <span id="news-filter">| Today</span></h5>
            <div class="activity" id="news-activity">
                No recent news added.
            </div>
            <a href="http://localhost/websitebinan/public/newsevents/1" class="news-link">News&Events <i class="bi bi-box-arrow-up-right"></i></a>
        </div>
    </div>
    <div class="highlight-card">
        <ul class="nav nav-tabs" id="highlightTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab">Top Views Page</button>
            </li>
            <!-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="referrers-tab" data-bs-toggle="tab" data-bs-target="#referrers" type="button" role="tab">Top Referrers</button>
            </li> -->
        </ul>
        <div class="tab-content" id="highlightTabContent">
    <div class="tab-pane fade show active" id="posts" role="tabpanel">
        <ul class="list-group">
            <?php if ($topPage): ?>
                <li class="list-group-item"><?php echo htmlspecialchars($topPage['page_name']); ?> <span class="float-end"><?php echo $topPage['visit_count']; ?> Views</span></li>
            <?php else: ?>
                <li class="list-group-item">No data available</li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="tab-pane fade" id="referrers" role="tabpanel">
        <ul class="list-group">
            <li class="list-group-item">Referrer 1 <span class="float-end">150 Views</span></li>
            <li class="list-group-item">Referrer 2 <span class="float-end">120 Views</span></li>
        </ul>
    </div>
</div>
</div>
</section>