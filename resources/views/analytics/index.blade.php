<x-app-layout>
    <x-slot name="title">
        分析ページ
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          分析ページ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-3 md:grid-cols-2">
                        <p class="text-lg font-medium">現在の応募状況</p>
                        <div class="col-end-4 col-span-2 md:col-end-3 md:col-span-1">
                          <form name="department_counts" class="grid grid-cols-4" action="/analytics/ajax/department" method="post">
                              <select id="department" class="col-span-3 block mt-1 ml-14 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    name="department" required>
                                    <option value="">全ての部署</option>
                              @foreach($departments as $department)
                                  <option value="{{ $department->id }}">{{ $department->name }}</option>
                              @endforeach
                              </select>
                              <button id="change" class="ml-8 bg-blue-500 text-xs md:text-sm hover:bg-blue-800 text-white py-2 px-2 rounded select-none">
                                  絞る
                              </button>
                          </form>
                        </div>
                    </div>
                    
                    <div class="mt-2">
                        <canvas id="currentStatus"></canvas>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div>
                        <p class="text-lg font-medium">今までの選考進行状況</p>
                        <canvas id="selection"></canvas>
                    </div>
                    <div class="mt-6">
                        <p class="text-lg font-medium">選考の進度確率</p>
                        <form name="status" action="/analytics/ajax/status" method="post">
                            <div class="grid grid-cols-10">
                                <select class="col-span-4 w-4/5 block mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            name="before_status" required>
                                    @foreach($status as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <p class="text-lg font-medium mt-3">→</p>
                                <select class="col-span-4 w-4/5 block mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            name="after_status" required>
                                    @foreach($status as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <button id="change_status" class="w-full bg-blue-500 text-xs md:text-sm hover:bg-blue-800 text-white py-2 px-2 rounded select-none">
                                  確率
                                </button>
                            </div>
                            <p id="probability" class="text-center text-3xl mt-4"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
      // ラベル
      var status_labels = @json($status_list);
      var selection_labels = [
        "応募",
        "書類選考",
        "1次選考",
        "2次選考",
        "内定",
        "内定承諾",
        "不合格"
      ];
      
      // データ
      var applicants = @json($applicant_counts);
      var selections = @json($status_counts);
      drawChart();
      drawChartSelection();

      function drawChart() {
        var ctx = document.getElementById("currentStatus");
        window.currentStatus = new Chart(ctx, {
          type: 'bar',
          data : {
            labels: status_labels,
            datasets: [
              {
                label: '応募者',
                data: applicants,
                borderColor: "rgba(0,245,245,1)",
                backgroundColor: "rgba(0,250,250,1)"
              },
            ]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  min: 0
                }
              }]
            }
          }
        });
      }


      function drawChartSelection() {
        var ctx = document.getElementById("selection");
        window.selection = new Chart(ctx, {
          type: 'bar',
          data : {
            labels: selection_labels,
            datasets: [
              {
                label: '応募者',
                data: selections,
                borderColor: "rgba(148,139,219,1)",
                backgroundColor: "rgba(148,139,219,1)"
              },
            ]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  min: 0
                }
              }]
            }
          }
        });
      }
      
      jQuery(function () {
        jQuery('#change').on('click', function(e) {
          e.preventDefault();
          if(currentStatus) {
            currentStatus.destroy();
          }
          var obj = document.forms["department_counts"];
          $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/analytics/ajax/department',
            type: 'POST',
            data: $(obj).serialize(),
            dataType: 'json'
          }).done(function(data) {
            // console.log("成功");
            applicants = data;
            drawChart();
          }).fail(function(data) {
            console.log("失敗");
          });
        });

        jQuery('#change_status').on('click', function(e) {
          e.preventDefault();
          var obj2 = document.forms["status"];
          $.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url: '/analytics/ajax/status',
            type: 'POST',
            data: $(obj2).serialize(),
            dataType: 'json'
          }).done(function(data2) {
            // console.log(data2[0]);
            $('#probability').text(data2[1] + "件 → " + data2[2] + "件   " + data2[0] + "%");
            // $('#probability').text(data2[0] + "%");
          }).fail(function(data2) {
            console.log("失敗");
          });
        });
      });
    </script>
</x-app-layout>
