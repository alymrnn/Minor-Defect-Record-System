<script type="text/javascript">
   $(document).ready(function() {
      fetch_defect_category_list();
      fetch_year_list();
      fetch_month_list();
      fetch_section_list();
      fetch_line_list();
      fetch_process_list();
      fetch_line_category_list();

      $('#week_container').empty().html('<p class="text-muted text-xs">Select year and month.</p>');

      // Detect when year/month checkboxes change
      $(document).on('change', '.year-check, .month-check', function() {
         const selectedYears = $('.year-check:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedMonths = $('.month-check:checked').map(function() {
            return $(this).val();
         }).get();

         console.log("Selected years:", selectedYears);
         console.log("Selected months:", selectedMonths);

         if (selectedYears.length > 0 && selectedMonths.length > 0) {
            fetch_week_list(selectedYears, selectedMonths);
         } else {
            $('#week_container').empty().html('<p class="text-muted text-xs">Select year and month.</p>');
         }
      });

      // const now = new Date();
      // if (!$("#d_year").val()) {
      //    $("#d_year").val(now.getFullYear());
      // }
      // if (!$("#d_month").val()) {
      //    $("#d_month").val(now.getMonth() + 1);
      // }

      // fetch_year_month_options().then(() => {
      //    generate_weekly_dashboard_filter();
      // });

      // setInterval(() => {
      //    generate_weekly_dashboard_filter();
      // }, 300000); // refersh every 5 mins
   });

   const generate_weekly_dashboard_filter = () => {
      Swal.fire({
         title: 'Loading...',
         text: 'Fetching dashboard data',
         allowOutsideClick: false,
         background: '#1b263b',
         color: '#f8f9fa',
         didOpen: () => {
            Swal.showLoading();
         }
      });

      Promise.all([
            fetch_weekly_defect_category_count(),
            fetch_weekly_defect_per_section()
         ])
         .then(() => {
            document.getElementById("weekly_top_lines_based_on_defect_category_chart").innerHTML = `
               <p id="weekly_top_lines_chart_placeholder" class="mb-0 text-info small">
                  Select from Weekly Record per Defect Category to generate Top 10 Lines of Selected Defect Category per Week
               </p>
         `;

            Swal.close();
         })
         .catch((error) => {
            Swal.close();
            Swal.fire("Error", "Failed to load some data: " + error, "error");
         });
   };

   const fetch_year_month_options = () => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: "POST",
            data: {
               method: "fetch_year_month_options"
            },
            dataType: "json",
            success: function(data) {
               const yearSelect = $("#d_year");
               const monthSelect = $("#d_month");

               yearSelect.empty();
               monthSelect.empty();

               data.years.forEach(year => {
                  yearSelect.append(
                     $("<option>", {
                        value: year,
                        text: year
                     })
                  );
               });

               const monthNames = [
                  "January", "February", "March", "April", "May", "June",
                  "July", "August", "September", "October", "November", "December"
               ];

               data.months.forEach(month => {
                  monthSelect.append(
                     $("<option>", {
                        value: month,
                        text: monthNames[month - 1]
                     })
                  );
               });

               const now = new Date();
               const currentYear = now.getFullYear();
               const currentMonth = now.getMonth() + 1;

               yearSelect.val(currentYear);
               monthSelect.val(currentMonth);

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", error);
               reject(error);
            }
         });
      });
   };

   function styledChartTitle(text) {
      return `<span style="
              font-family: Poppins, sans-serif;
              font-weight: 600;
              font-size: 13px;
           ">${text}</span>`;
   }

   const fetch_weekly_defect_category_count = () => {
      return new Promise((resolve, reject) => {
         const year = $("#d_year").val();
         const month = $("#d_month").val();

         $.ajax({
            url: "process/weekly_monitoring_p.php",
            type: "POST",
            data: {
               method: "fetch_weekly_defect_category_count",
               year: year,
               month: month
            },
            dataType: "json",
            success: function(data) {
               const categories = [
                  "Exposed Wire/Junction",
                  "Missing Marking",
                  "Clamp Defect",
                  "Insufficient taping",
                  "Insufficient taping (with dimension requirement)",
                  "Missing Tape",
                  "Option Tape",
                  "Taping Defect",
                  "Damage Parts",
                  "La Terminal Defect",
                  "Wrong View",
                  "Foreign Material"
               ];

               // Get unique week ranges
               const weeks = [...new Set(data.map(r => r.week_range))];

               // Prepare series data
               const seriesData = weeks.map(week => ({
                  name: week,
                  data: categories.map(cat => {
                     const found = data.find(d => d.defect_category === cat && d.week_range === week);
                     return found ? found.defect_count : 0;
                  })
               }));

               // Build dynamic chart title
               const monthNames = [
                  "January", "February", "March", "April", "May", "June",
                  "July", "August", "September", "October", "November", "December"
               ];
               const titleText = `Weekly Record per Defect Category (${monthNames[month - 1]} ${year})`;

               Highcharts.chart("weekly_defect_category_count_chart", {
                  chart: {
                     type: "column",
                     height: "290px",
                     style: {
                        fontFamily: "Poppins, sans-serif"
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(titleText),
                     align: "left"
                  },
                  xAxis: {
                     categories: categories,
                     title: {
                        text: null
                     },
                     labels: {
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        enabled: true,
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     headerFormat: "<b>{point.key}</b><br>",
                     pointFormat: "{series.name}: <b>{point.y}</b> defects<br>",
                     style: {
                        fontFamily: "Poppins, sans-serif",
                        fontSize: "11px"
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     itemStyle: {
                        fontFamily: "Poppins, sans-serif",
                        fontSize: "10px"
                     },
                     itemMarginTop: 0,
                     itemMarginBottom: 0,
                     symbolHeight: 8,
                     symbolWidth: 8,
                     symbolRadius: 2
                  },
                  plotOptions: {
                     column: {
                        borderRadius: 3,
                        pointPadding: 0.2,
                        groupPadding: 0.2,
                        cursor: "pointer",
                        point: {
                           events: {
                              click: function() {
                                 const defectCategory = this.category;
                                 const selectedYear = $("#d_year").val();
                                 const selectedMonth = $("#d_month").val();

                                 Swal.fire({
                                    title: "Loading...",
                                    text: `Fetching top lines for ${defectCategory}...`,
                                    allowOutsideClick: false,
                                    background: '#1b263b',
                                    color: '#f8f9fa',
                                    didOpen: () => {
                                       Swal.showLoading();
                                    }
                                 });

                                 fetch_weekly_top_lines_based_on_defect_category(defectCategory, selectedYear, selectedMonth)
                                    .then(() => {
                                       Swal.close();
                                    })
                                    .catch(() => {
                                       Swal.fire("Error", "Failed to load data", "error");
                                    });
                              }
                           }
                        },
                        dataLabels: {
                           enabled: true,
                           style: {
                              fontFamily: "Poppins, sans-serif",
                              fontSize: "9px"
                           }
                        }
                     }
                  },
                  colors: [
                     "#0D47A1", "#1976D2", "#64B5F6", // Blue shades
                     "#1B5E20", "#388E3C", "#81C784" // Green shades
                  ],
                  series: seriesData
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", error);
               reject(error);
            }
         });
      });
   };

   const fetch_weekly_top_lines_based_on_defect_category = (defectCategory, selectedYear, selectedMonth) => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: "process/weekly_monitoring_p.php",
            type: "POST",
            data: {
               method: "fetch_weekly_top_lines_based_on_defect_category",
               defect_category: defectCategory,
               year: selectedYear,
               month: selectedMonth
            },
            dataType: "json",
            success: function(data) {
               $('#weekly_top_lines_chart_placeholder').hide();

               const weeks = [...new Set(data.map(r => r.week_range))];

               // 1. Compute total defects per line
               const lineTotals = {};
               data.forEach(d => {
                  lineTotals[d.line_no] = (lineTotals[d.line_no] || 0) + d.defect_count;
               });

               // 2. Sort lines by total defects (descending)
               const lines = Object.keys(lineTotals).sort((a, b) => lineTotals[b] - lineTotals[a]);

               // 3. Build series data based on sorted lines
               const seriesData = weeks.map(week => ({
                  name: week,
                  data: lines.map(line => {
                     const found = data.find(d => d.line_no === line && d.week_range === week);
                     return found ? found.defect_count : 0;
                  })
               }));

               // 🔹 Convert month number to full name
               const monthNames = [
                  "January", "February", "March", "April", "May", "June",
                  "July", "August", "September", "October", "November", "December"
               ];
               const monthName = monthNames[selectedMonth - 1];

               Highcharts.chart("weekly_top_lines_based_on_defect_category_chart", {
                  chart: {
                     type: "column",
                     height: "290px",
                     style: {
                        fontFamily: "Poppins, sans-serif"
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Weekly Top 10 Lines (${monthName} ${selectedYear}) with ${defectCategory}`),
                     align: "left"
                  },
                  xAxis: {
                     categories: lines,
                     labels: {
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     labels: {
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     headerFormat: "<b>{point.key}</b><br>",
                     pointFormat: "{series.name}: <b>{point.y}</b> defects<br>",
                     style: {
                        fontFamily: "Poppins, sans-serif",
                        fontSize: "11px"
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     itemStyle: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '10px'
                     },
                     symbolHeight: 8,
                     symbolWidth: 8,
                     symbolRadius: 2
                  },
                  plotOptions: {
                     column: {
                        borderRadius: 3,
                        pointPadding: 0.2,
                        groupPadding: 0.2,
                        dataLabels: {
                           enabled: true,
                           style: {
                              fontFamily: "Poppins, sans-serif",
                              fontSize: "9px"
                           }
                        }
                     }
                  },
                  colors: ["#1565C0", "#1E88E5", "#42A5F5", "#F57C00", "#FB8C00", "#FFB74D"],
                  series: seriesData
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", error);
               reject(error);
            }
         });
      });
   };

   const fetch_weekly_defect_per_section = () => {
      return new Promise((resolve, reject) => {
         const year = $("#d_year").val();
         const month = $("#d_month").val();

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: "POST",
            data: {
               method: "fetch_weekly_defect_per_section",
               year: year,
               month: month
            },
            dataType: "json",
            success: function(data) {
               if (data.error) {
                  console.error(data.error);
                  return;
               }

               // Extract unique sections (for x-axis)
               const rawSections = [...new Set(data.map(item => item.section))];
               const sections = rawSections.map(sec => `Section ${sec}`);

               // Extract unique weeks (for series)
               const weeks = [...new Set(data.map(item => item.week_range))];

               // Build week -> section defect counts
               const weekMap = {};
               weeks.forEach(week => {
                  weekMap[week] = Array(rawSections.length).fill(0);
               });

               data.forEach(item => {
                  const sectionIndex = rawSections.indexOf(item.section);
                  const weekName = item.week_range;
                  if (sectionIndex !== -1 && weekMap[weekName]) {
                     weekMap[weekName][sectionIndex] = item.defect_count;
                  }
               });

               // Convert to Highcharts series
               const seriesData = weeks.map(week => ({
                  name: week,
                  data: weekMap[week]
               }));

               // Build dynamic chart title
               const monthNames = [
                  "January", "February", "March", "April", "May", "June",
                  "July", "August", "September", "October", "November", "December"
               ];
               const titleText = `Weekly Record per Section (${monthNames[month - 1]} ${year})`;

               Highcharts.chart("weekly_defect_per_section_chart", {
                  chart: {
                     type: "column",
                     height: "290px",
                     style: {
                        fontFamily: "Poppins, sans-serif"
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(titleText),
                     align: "left"
                  },
                  xAxis: {
                     categories: sections,
                     labels: {
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     labels: {
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     headerFormat: "<b>{point.key}</b><br>",
                     pointFormat: "{series.name}: <b>{point.y}</b> defects<br>",
                     style: {
                        fontFamily: "Poppins, sans-serif",
                        fontSize: "11px"
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     itemStyle: {
                        fontFamily: "Poppins, sans-serif",
                        fontSize: "10px"
                     },
                     symbolHeight: 8,
                     symbolWidth: 8,
                     symbolRadius: 2
                  },
                  plotOptions: {
                     column: {
                        borderRadius: 3,
                        pointPadding: 0.2,
                        groupPadding: 0.2,
                        dataLabels: {
                           enabled: true,
                           style: {
                              fontFamily: "Poppins, sans-serif",
                              fontSize: "9px"
                           }
                        }
                     }
                  },
                  colors: [
                     "#0D47A1", "#1976D2", "#64B5F6", // Blue shades
                     "#1B5E20", "#388E3C", "#81C784", // Green shades
                  ],
                  series: seriesData
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", error);
               reject(error);
            }
         });
      });
   };

   document.addEventListener("DOMContentLoaded", function() {
      const sidebar = document.getElementById('sidebarOverall');
      const toggleBtn = document.getElementById('toggleBtnOverall');
      const toggleIcon = toggleBtn.querySelector('i');
      const mainFooter = document.getElementById('mainFooterOverall');

      const bsCollapse = new bootstrap.Collapse(sidebar, {
         toggle: false
      });

      toggleBtn.addEventListener('click', function() {
         const isShown = sidebar.classList.contains('show');

         if (isShown) {
            bsCollapse.hide();
            toggleIcon.classList.replace('fa-times', 'fa-bars');
            mainFooter.style.marginLeft = '0';
         } else {
            bsCollapse.show();
            toggleIcon.classList.replace('fa-bars', 'fa-times');
            mainFooter.style.marginLeft = '16.6667%';
         }
      });
   });

   document.addEventListener('scroll', function() {
      const header = document.getElementById('stickyHeaderOverall');
      if (window.scrollY > 0) {
         header.classList.add('is-sticky');
      } else {
         header.classList.remove('is-sticky');
      }
   });



   // =================================================================================================================
   // OVERALL MONITORING V2
   const fetch_defect_category_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: "POST",
         data: {
            method: 'fetch_defect_category_list'
         },
         dataType: "json",
         success: function(response) {
            let container = $('#defect_category_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No categories found.</p>');
               return;
            }

            // Add Mark All / Unmark All button
            const markAllButton = $(`
               <button id="markAllBtn" class="btn btn-xs btn-outline-info m-0 p-0 m-1">
                  &nbsp;Mark All&nbsp;
               </button>
            `);
            container.append(markAllButton);

            // Generate button-style checkboxes
            response.forEach(item => {
               const buttonCheckbox = `
               <input type="checkbox" class="btn-check defect-checkbox" id="defect_${item}" value="${item}" autocomplete="off">
               <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1" for="defect_${item}">
                  &nbsp;${item}&nbsp;
               </label>
            `;
               container.append(buttonCheckbox);
            });

            // Toggle all function
            $(document).off('click', '#markAllBtn').on('click', '#markAllBtn', function() {
               const allBoxes = $('.defect-checkbox');
               const allChecked = allBoxes.filter(':checked').length === allBoxes.length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         },

         error: function(xhr, status, error) {
            console.error("Error fetching defect categories:", error);
         }
      });
   };

   const fetch_year_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: "POST",
         data: {
            method: 'fetch_year_list'
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#year_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No year data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_years_btn'
            });
            container.append(markAllBtn);

            // Generate year buttons
            response.forEach(year => {
               const buttonCheckbox = `
               <input type="checkbox" class="btn-check year-check" id="year_${year}" value="${year}" autocomplete="off">
               <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1 w-25" for="year_${year}">
                  &nbsp;${year}&nbsp;
               </label>
            `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic
            $('#mark_all_years_btn').on('click', function() {
               const allBoxes = $('.year-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         }
      });
   };

   const fetch_month_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: "POST",
         data: {
            method: 'fetch_month_list'
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#month_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No month data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_months_btn'
            });
            container.append(markAllBtn);

            // Generate month buttons
            response.forEach(month => {
               const buttonCheckbox = `
               <input type="checkbox" class="btn-check month-check" id="month_${month.num}" value="${month.num}" autocomplete="off">
               <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1 w-25" for="month_${month.num}">
                  &nbsp;${month.name}&nbsp;
               </label>
            `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic
            $('#mark_all_months_btn').on('click', function() {
               const allBoxes = $('.month-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }

               // Trigger week update when mark/unmark all is clicked
               const selectedYears = $('.year-check:checked').map(function() {
                  return $(this).val();
               }).get();

               const selectedMonths = $('.month-check:checked').map(function() {
                  return $(this).val();
               }).get();

               if (selectedYears.length > 0 && selectedMonths.length > 0) {
                  fetch_week_list(selectedYears, selectedMonths);
               } else {
                  $('#week_container').empty().html('<p class="text-muted text-xs">Select year and month.</p>');
               }
            });

            // Also trigger weeks when any single month checkbox is changed
            $(document).on('change', '.month-check', function() {
               const selectedYears = $('.year-check:checked').map(function() {
                  return $(this).val();
               }).get();

               const selectedMonths = $('.month-check:checked').map(function() {
                  return $(this).val();
               }).get();

               if (selectedYears.length > 0 && selectedMonths.length > 0) {
                  fetch_week_list(selectedYears, selectedMonths);
               } else {
                  $('#week_container').empty().html('<p class="text-muted text-xs">Select year and month.</p>');
               }
            });
         }
      });
   };


   const fetch_week_list = (years, months) => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         data: {
            method: 'fetch_week_list',
            years: years,
            months: months
         },
         dataType: 'json',
         success: function(response) {
            console.log("Weeks response:", response);

            let container = $('#week_container');
            container.empty();

            if (!response || response.length === 0) {
               container.html('<p class="text-muted text-xs">No week data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_weeks_btn'
            });
            container.append(markAllBtn);

            // Loop through months
            response.forEach(monthData => {
               const monthLabel = $('<div>', {
                  class: 'mt-2 text-xs text-right',
                  text: monthData.monthLabel
               });
               container.append(monthLabel);

               // Append week buttons for this month
               monthData.weeks.forEach(week => {
                  const safeId = week.replace(/\s|\(|\)|–/g, '_');
                  const buttonCheckbox = `
                     <input type="checkbox" class="btn-check week-check" id="${safeId}" value="${week}" autocomplete="off">
                     <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1" for="${safeId}">
                     &nbsp;${week}&nbsp;
                     </label><br>
                  `;
                  container.append(buttonCheckbox);
               });
            });

            // Mark/Unmark All logic
            $('#mark_all_weeks_btn').on('click', function() {
               const allBoxes = $('.week-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         },
         error: function(xhr, status, error) {
            console.error("Error fetching weeks:", error);
         }
      });
   };

   const fetch_section_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         data: {
            method: 'fetch_section_list'
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#section_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No section data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_sections_btn'
            });
            container.append(markAllBtn);

            // Generate section checkboxes
            response.forEach(section => {
               const safeId = section.replace(/\s+/g, '_');
               const buttonCheckbox = `
                  <input type="checkbox" class="btn-check section-check" id="section_${safeId}" value="${section}" autocomplete="off">
                  <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1 w-25" for="section_${safeId}">
                     &nbsp;${section}&nbsp;
                  </label>
               `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic
            $('#mark_all_sections_btn').on('click', function() {
               const allBoxes = $('.section-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }

               fetch_line_list();
            });

            // When any section checkbox changes
            $('.section-check').on('change', fetch_line_list);
         }
      });
   };

   // Function to update line list based on selected sections
   const fetch_line_list = () => {
      const selectedSections = $('.section-check:checked').map(function() {
         return $(this).val();
      }).get();

      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         data: {
            method: 'fetch_line_list',
            section: selectedSections
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#line_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No line data found for selected section(s).</p>');
               return;
            }

            // Make container scrollable and fixed height
            // container.css({
            //    'height': '250px',
            //    'overflow-y': 'auto'
            // });

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_lines_btn'
            });
            container.append(markAllBtn);

            response.forEach(line => {
               const safeId = line.replace(/\s+/g, '_');
               const buttonCheckbox = `
                  <input type="checkbox" class="btn-check line-check" id="line_${safeId}" value="${line}" autocomplete="off">
                  <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1 w-25" for="line_${safeId}">
                     &nbsp;${line}&nbsp;
                  </label>
               `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic for line list
            $('#mark_all_lines_btn').on('click', function() {
               const allBoxes = $('.line-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         }
      });
   };

   const fetch_process_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         data: {
            method: 'fetch_process_list'
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#process_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No process data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_process_btn'
            });
            container.append(markAllBtn);

            // Generate process checkboxes
            response.forEach(process => {
               const safeId = process.replace(/\s+/g, '_');
               const buttonCheckbox = `
                  <input type="checkbox" class="btn-check process-check" id="process_${safeId}" value="${process}" autocomplete="off">
                  <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1" for="process_${safeId}">
                     &nbsp;${process}&nbsp;
                  </label>
               `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic
            $('#mark_all_process_btn').on('click', function() {
               const allBoxes = $('.process-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         }
      });
   };

   const fetch_line_category_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         data: {
            method: 'fetch_line_category_list'
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#line_category_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No line category data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_line_category_btn'
            });
            container.append(markAllBtn);

            // Generate line catgeory checkboxes
            response.forEach(line_category => {
               const safeId = line_category.replace(/\s+/g, '_');
               const buttonCheckbox = `
                  <input type="checkbox" class="btn-check line-category-check" id="line_category_${safeId}" value="${line_category}" autocomplete="off">
                  <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1 w-25" for="line_category_${safeId}">
                     &nbsp;${line_category}&nbsp;
                  </label>
               `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic
            $('#mark_all_line_category_btn').on('click', function() {
               const allBoxes = $('.line-category-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         }
      });
   };
</script>