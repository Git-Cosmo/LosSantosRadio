// Handle confirmation dialogs for forms with data-confirm attribute
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('button[data-confirm]').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    if (!confirm(this.getAttribute('data-confirm'))) {
                        e.preventDefault();
                    }
                });
            });
        });
