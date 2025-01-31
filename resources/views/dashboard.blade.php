<x-app-layout>
<link href="https://cdn.jsdelivr.net/npm/gridstack@7.2.3/dist/gridstack.min.css" rel="stylesheet">

<div class="bg-red-600 flex">
    <livewire:weather-widget />
    <livewire:spotify-widget />
    <livewire:forex-widget />
    <livewire:news-widget />
    <livewire:Music-widget />
  </div>

  <button class="bg-white p-6 rounded-full absolute z-6 bottom-5 right-5" style="background-color: #16526E;">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-plus">
      <circle cx="12" cy="12" r="10"/>
      <path d="M8 12h8"/>
      <path d="M12 8v8"/>
    </svg>
  </button>
  
  <div class="grid-stack"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gridstack@7.2.3/dist/gridstack-all.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

  const ctx = document.getElementById('myChart');
  const chartConfig = {
    type: 'bar',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 50],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true }
      }
    }
  };
  new Chart(ctx, chartConfig);

  const grid = GridStack.init({
    column: 12,
    margin: 5,
    dragIn: true,
    acceptWidgets: true
  });

  document.querySelectorAll('.sidebar-widget').forEach(widget => {
    widget.addEventListener('dragstart', (e) => {
      const chartData = {
        config: chartConfig,
        w: parseInt(widget.dataset.w),
        h: parseInt(widget.dataset.h)
      };
      e.dataTransfer.setData('application/json', JSON.stringify(chartData));
    });
  });

  grid.el.addEventListener('drop', (e, previousNode) => {
        e.preventDefault();
        const data = JSON.parse(e.dataTransfer.getData('application/json'));

        const newWidget = document.createElement('div');
        newWidget.className = 'grid-stack-item p-4 rounded';
        newWidget.innerHTML = `
            <div class="grid-stack-item-content relative">
                <button class="delete-btn absolute top-0 right-0 bg-red-500 text-white px-2 pb-1 rounded hover:bg-red-600">×</button>
                <canvas></canvas>
            </div>
        `;

        grid.addWidget(newWidget, {
            w: data.w,
            h: data.h,
            autoPosition: true
        });
        newWidget.querySelector('.delete-btn').addEventListener('click', () => {
            grid.removeWidget(newWidget);
        });

        const newCtx = newWidget.querySelector('canvas');
        new Chart(newCtx, data.config);
    });

    grid.load([
        { 
            x: 0, y: 0, w: 6, h: 1, 
            content: '<div class="grid-stack-item-content relative"><button class="delete-btn absolute top-0 right-0 bg-red-500 text-white px-2 pb-1 rounded hover:bg-red-600">×</button>First Widget</div>' 
        },
        { 
            x: 6, y: 0, w: 6, h: 2, 
            content: '<div class="grid-stack-item-content relative"><button class="delete-btn absolute top-0 right-0 bg-red-500 text-white px-2 pb-1 rounded hover:bg-red-600">×</button>Second Widget</div>' 
        },
    ]);

    grid.el.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const widget = this.closest('.grid-stack-item');
            grid.removeWidget(widget);
        });
    });

  grid.el.addEventListener('dragover', (e) => {
    e.preventDefault();
  });
});
</script>

</x-app-layout>