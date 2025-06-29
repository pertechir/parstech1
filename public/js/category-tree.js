document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.category-tree-toggle').forEach(function(toggle){
        toggle.addEventListener('click', function(e){
            e.stopPropagation();
            let targetSelector = this.getAttribute('data-target');
            let target = document.querySelector(targetSelector);
            if(target) {
                target.classList.toggle('show');
                this.classList.toggle('collapsed');
                let icon = this.querySelector('i');
                if(icon) {
                    icon.classList.toggle('fa-angle-left');
                    icon.classList.toggle('fa-angle-down');
                }
            }
        });
    });
});
