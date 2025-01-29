<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/gridstack@7.2.3/dist/gridstack.min.css" rel="stylesheet">
    <div class="grid-stack"></div>
    <script src="https://cdn.jsdelivr.net/npm/gridstack@7.2.3/dist/gridstack-all.js"></script>

    <script type="text/javascript">

      document.addEventListener('DOMContentLoaded', function() {
        const grid = GridStack.init({
          column: 12, 
          margin: 5 ,
        });
        const items = [
          { x:0, y:0, w:6, h:1, content: '<div class="grid-stack-item-content">First Widget</div>' },
          { x:6, y:0, w:6, h:2, content: '<div class="grid-stack-item-content">Second Widget</div>' }
        ];
        
        grid.load(items);
      });
    </script>
</x-app-layout>