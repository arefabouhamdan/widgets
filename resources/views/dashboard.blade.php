<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/gridstack@7.2.3/dist/gridstack.min.css" rel="stylesheet">

    <div>
      <div class="bg-red-600 flex">
         <livewire:weather-widget />
         <livewire:spotify-widget />
         <livewire:forex-widget />
         <livewire:news-widget />
         <livewire:Music-widget />
      </div>
      <button class="bg-white p-6 rounded-full absolute z-6 bottom-5 right-5" style="background-color: #16526E;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-plus"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
      </button>
      <div class="grid-stack "></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/gridstack@7.2.3/dist/gridstack-all.js"></script>
    <script type="text/javascript">document.addEventListener("DOMContentLoaded", function () {
    const grid = GridStack.init({
        column: 12,
        margin: 5,
        dragIn: true,
    });

    document.querySelectorAll(".sidebar-widget").forEach((widget) => {
        widget.addEventListener("dragstart", (e) => {
            e.dataTransfer.setData(
                "text/plain",
                JSON.stringify({
                    w: parseInt(e.target.dataset.w),
                    h: parseInt(e.target.dataset.h),
                    content: e.target.textContent,
                })
            );

            e.target.classList.add("dragging");
        });

        widget.addEventListener("dragend", (e) => {
            e.target.classList.remove("dragging");
        });
    });

    grid.el.addEventListener("dragover", (e) => {
        e.preventDefault();
    });

    grid.el.addEventListener("drop", (e) => {
        e.preventDefault();
        const data = JSON.parse(e.dataTransfer.getData("text/plain"));

        const node = {
            w: data.w,
            h: data.h,
            content: data.content,
        };

        grid.addWidget(node);
    });

    grid.load([
        { x: 0, y: 0, w: 6, h: 1, content: "First Widget" },
        { x: 6, y: 0, w: 6, h: 2, content: "Second Widget" },
    ]);
});
</script>
</x-app-layout>