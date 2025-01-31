<x-app-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/gridstack@7.2.3/dist/gridstack.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div id="settingsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg max-w-md mx-auto mt-20">
            <div class="flex justify-between mb-4">
                <h3 class="text-xl font-bold">Widget Settings</h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">×</button>
            </div>
            <form id="widgetSettingsForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Title</label>
                    <input type="text" id="widgetTitle" class="mt-1 block w-full rounded border-gray-300"   >
                </div>
                <div>
                    <label class="block text-sm font-medium">Description</label>
                    <textarea id="widgetDescription" class="mt-1 block w-full rounded border-gray-300"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Chart Color</label>
                    <input type="color" id="chartColor" class="mt-1 block w-full rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium">Date Range</label>
                    <div class="flex space-x-2">
                        <input type="date" id="startDate" class="mt-1 block rounded border-gray-300">
                        <input type="date" id="endDate" class="mt-1 block rounded border-gray-300">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Save Changes</button>
            </form>
        </div>
    </div>

    <div>
        <div class="bg-red-600 flex p-4 mb-4 hidden" id="top-bar">
            <livewire:weather-widget />
            <livewire:spotify-widget />
            <livewire:forex-widget />
            <livewire:news-widget />
            <livewire:Music-widget />
        </div>

        <button class="bg-white p-6 rounded-full fixed z-10 bottom-5 right-5 shadow-lg add-btn" style="background-color: #16526E;">
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
        const addBtn = document.querySelector('.add-btn');
        addBtn.addEventListener('click', () => {
            document.getElementById('top-bar').classList.toggle('hidden');
        });

        const grid = GridStack.init({
            column: 12,
            margin: 5,
            dragIn: true,
            acceptWidgets: true,
        });

        let currentWidget = null;
        let currentChart = null;

        function setupWidgetControls(widget) {
            const editBtn = widget.querySelector('.edit-btn');
            const deleteBtn = widget.querySelector('.delete-btn');

            editBtn.addEventListener('click', () => {
                currentWidget = widget;
                const config = JSON.parse(widget.dataset.config);
                
                document.getElementById('widgetTitle').value = config.title || '';
                document.getElementById('widgetDescription').value = config.description || '';
                document.getElementById('chartColor').value = config.color || '#3B82F6';
                document.getElementById('startDate').value = config.startDate || '';
                document.getElementById('endDate').value = config.endDate || '';
                document.getElementById('settingsModal').classList.remove('hidden');
            });

            deleteBtn.addEventListener('click', () => {
                grid.removeWidget(widget);
            });
        }

        document.getElementById('widgetSettingsForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const config = {
                ...JSON.parse(currentWidget.dataset.config),
                title: document.getElementById('widgetTitle').value,
                description: document.getElementById('widgetDescription').value,
                color: document.getElementById('chartColor').value,
                startDate: document.getElementById('startDate').value,
                endDate: document.getElementById('endDate').value,
            };

            const titleEl = currentWidget.querySelector('.widget-title');
            const descEl = currentWidget.querySelector('.widget-description');
            if (titleEl) titleEl.textContent = config.title;
            if (descEl) descEl.textContent = config.description;

            if (currentWidget.chart) {
                currentWidget.chart.destroy();
                const ctx = currentWidget.querySelector('canvas');
                const newConfig = getChartConfig(config);
                currentWidget.chart = new Chart(ctx, newConfig);
            }

            currentWidget.dataset.config = JSON.stringify(config);
            document.getElementById('settingsModal').classList.add('hidden');
        });

        document.getElementById('closeModal').addEventListener('click', () => {
            document.getElementById('settingsModal').classList.add('hidden');
        });

        function createWidget(data) {
            const newWidget = document.createElement('div');
            newWidget.className = 'grid-stack-item p-4 rounded';
            newWidget.innerHTML = `
                <div class="grid-stack-item-content">
                    <div class="absolute top-5 right-5 z-10">
                        <button class="edit-btn bg-blue-500 text-white px-2 rounded hover:bg-blue-600">Edit</button>
                        <button class="delete-btn bg-red-500 text-white px-2 rounded hover:bg-red-600">×</button>
                    </div>
                    <div class="p-4 h-full flex flex-col">
                        <h3 class="widget-title font-bold mb-2">${data.config.title || 'New Widget'}</h3>
                        <p class="widget-description text-sm text-gray-600 mb-4">${data.config.description || 'Customizable widget'}</p>
                        <div class="flex-1">
                            <canvas class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
            `;

            const initialConfig = {
                ...data.config,
                title: data.config.title || 'New Widget',
                description: data.config.description || 'Customizable widget',
                color: data.config.color || '#3B82F6'
            };

            newWidget.dataset.config = JSON.stringify(initialConfig);
            grid.addWidget(newWidget, { 
                w: data.w, 
                h: data.h,
                autoPosition: true
            });

            const ctx = newWidget.querySelector('canvas');
            newWidget.chart = new Chart(ctx, getChartConfig(initialConfig));
            setupWidgetControls(newWidget);
            return newWidget;
        }

        function getChartConfig(config) {
            return {
                type: 'bar',
                data: {
                    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                    datasets: [{
                        label: '# of Votes',
                        data: [12, 19, 3, 5, 2, 50],
                        backgroundColor: config.color || '#3B82F6',
                        borderColor: config.color || '#3B82F6',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 10 }
                        }
                    },
                    plugins: {
                        legend: { position: 'top' }
                    }
                }
            };
        }

        const initialWidgets = [
            { 
                w: 6, 
                h: 4,
                config: {
                    title: 'Sales Data',
                    description: 'Monthly sales performance',
                    color: '#3B82F6'
                }
            },
        ];

        initialWidgets.forEach(data => {
            createWidget({
                ...data,
                config: data.config
            });
        });

        document.querySelectorAll('.sidebar-widget').forEach(widget => {
            widget.addEventListener('dragstart', (e) => {
                const chartData = {
                    config: {
                        type: 'bar',
                        title: 'New Chart',
                        description: 'Drag-and-drop chart'
                    },
                    w: parseInt(widget.dataset.w),
                    h: parseInt(widget.dataset.h)
                };
                e.dataTransfer.setData('application/json', JSON.stringify(chartData));
            });
        });

        grid.el.addEventListener('drop', (e) => {
            e.preventDefault();
            const data = JSON.parse(e.dataTransfer.getData('application/json'));
            createWidget(data);
        });

        grid.el.addEventListener('dragover', (e) => e.preventDefault());
    });
    </script>
</body>
</html>
</x-app-layout>