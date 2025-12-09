document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const icecastFields = document.getElementById('icecast-fields');
    const shoutcastFields = document.getElementById('shoutcast-fields');

    function updateFields() {
        const type = typeSelect.value;
        icecastFields.style.display = type === 'icecast' ? 'block' : 'none';
        shoutcastFields.style.display = type === 'shoutcast' ? 'block' : 'none';
    }

    typeSelect.addEventListener('change', updateFields);
    updateFields(); // Initial check
});
