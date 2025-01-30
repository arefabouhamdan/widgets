<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/gridstack@7.2.3/dist/gridstack.min.css" rel="stylesheet">

    <div style="display: flex;">
      <div class="bg-white" style="height:100vh;">
         <livewire:weather-widget />
      </div>
      <div class="w-full">
        <div class="grid-stack w-full"></div>
        <button class="p4 bg-white">+</button>
      </div>
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