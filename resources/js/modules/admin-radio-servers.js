async function controlServer(serverId, action) {
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    try {
        const response = await fetch(`/admin/radio-servers/${serverId}/${action}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            window.showToast('success', data.message || `Server ${action} successful`);
            setTimeout(() => window.location.reload(), 1500);
        } else {
            window.showToast('error', data.message || `Failed to ${action} server`);
        }
    } catch (error) {
        window.showToast('error', `Error: ${error.message}`);
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

async function testServer(serverId) {
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    try {
        const response = await fetch(`/admin/radio-servers/${serverId}/test`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            window.showToast('success', data.message);
        } else {
            window.showToast('error', data.message);
        }
    } catch (error) {
        window.showToast('error', `Error: ${error.message}`);
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}
