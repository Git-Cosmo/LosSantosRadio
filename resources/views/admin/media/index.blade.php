<x-admin.layouts.app>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 style="font-size: 1.5rem; font-weight: 600;">
                <i class="fas fa-photo-video" style="color: var(--color-accent);"></i>
                Media Library
            </h1>
            <button onclick="document.getElementById('upload-modal').classList.remove('hidden')" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Media
            </button>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                <input type="text" id="search-media" placeholder="Search media..." class="form-input" style="max-width: 300px;">
                <select id="filter-type" class="form-input" style="max-width: 150px;">
                    <option value="">All Types</option>
                    <option value="image">Images</option>
                    <option value="video">Videos</option>
                    <option value="audio">Audio</option>
                    <option value="application">Documents</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div id="media-grid" class="media-grid">
                @forelse($media as $item)
                    <div class="media-item" data-id="{{ $item->id }}" data-type="{{ explode('/', $item->mime_type)[0] }}">
                        <div class="media-preview">
                            @if(str_starts_with($item->mime_type, 'image/'))
                                <img src="{{ $item->getUrl() }}" alt="{{ $item->name }}" loading="lazy">
                            @elseif(str_starts_with($item->mime_type, 'video/'))
                                <div class="media-type-icon">
                                    <i class="fas fa-video"></i>
                                </div>
                            @elseif(str_starts_with($item->mime_type, 'audio/'))
                                <div class="media-type-icon">
                                    <i class="fas fa-music"></i>
                                </div>
                            @else
                                <div class="media-type-icon">
                                    <i class="fas fa-file"></i>
                                </div>
                            @endif
                        </div>
                        <div class="media-info">
                            <span class="media-name" title="{{ $item->file_name }}">{{ $item->name }}</span>
                            <span class="media-size">{{ number_format($item->size / 1024, 1) }} KB</span>
                        </div>
                        <div class="media-actions">
                            <button onclick="copyToClipboard('{{ $item->getUrl() }}')" class="btn btn-sm btn-secondary" title="Copy URL">
                                <i class="fas fa-copy"></i>
                            </button>
                            <a href="{{ $item->getUrl() }}" target="_blank" class="btn btn-sm btn-secondary" title="View">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <button onclick="deleteMedia({{ $item->id }})" class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                        <i class="fas fa-photo-video" style="font-size: 4rem; color: var(--color-text-muted); margin-bottom: 1rem;"></i>
                        <h3 style="color: var(--color-text-secondary);">No Media Found</h3>
                        <p style="color: var(--color-text-muted);">Upload your first media file to get started.</p>
                    </div>
                @endforelse
            </div>

            @if($media->hasPages())
                <div style="margin-top: 1.5rem;">
                    {{ $media->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="upload-modal" class="modal hidden">
        <div class="modal-overlay" onclick="document.getElementById('upload-modal').classList.add('hidden')"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-upload"></i> Upload Media</h2>
                <button onclick="document.getElementById('upload-modal').classList.add('hidden')" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="upload-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file" class="form-label">Select File</label>
                        <div class="dropzone" id="dropzone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop files here or click to browse</p>
                            <p class="text-muted">Supported: JPG, PNG, GIF, WebP, PDF, MP3, MP4 (max 10MB)</p>
                            <input type="file" name="file" id="file" class="dropzone-input" accept=".jpg,.jpeg,.png,.gif,.webp,.svg,.pdf,.mp3,.mp4,.webm">
                        </div>
                        <div id="upload-preview" class="hidden" style="margin-top: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--color-bg-secondary); border-radius: 8px;">
                                <i class="fas fa-file" id="preview-icon"></i>
                                <span id="preview-name"></span>
                                <button type="button" onclick="clearUpload()" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="collection" class="form-label">Collection (Optional)</label>
                        <input type="text" name="collection" id="collection" class="form-input" placeholder="e.g., news, events, profiles">
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <button type="button" onclick="document.getElementById('upload-modal').classList.add('hidden')" class="btn btn-secondary">Cancel</button>
                        <button type="submit" id="upload-btn" class="btn btn-primary" disabled>
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .media-item {
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .media-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .media-preview {
            aspect-ratio: 1;
            background: var(--color-bg-tertiary);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .media-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .media-type-icon {
            font-size: 3rem;
            color: var(--color-text-muted);
        }

        .media-info {
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .media-name {
            font-weight: 500;
            font-size: 0.875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .media-size {
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }

        .media-actions {
            padding: 0.5rem 0.75rem;
            display: flex;
            gap: 0.5rem;
            border-top: 1px solid var(--color-border);
        }

        .modal {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal.hidden {
            display: none;
        }

        .modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            position: relative;
            background: var(--color-bg-primary);
            border-radius: 12px;
            width: 100%;
            max-width: 500px;
            margin: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--color-border);
        }

        .modal-header h2 {
            font-size: 1.25rem;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: var(--color-text-muted);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .dropzone {
            border: 2px dashed var(--color-border);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }

        .dropzone:hover,
        .dropzone.dragover {
            border-color: var(--color-accent);
            background: var(--color-bg-secondary);
        }

        .dropzone i {
            font-size: 2.5rem;
            color: var(--color-accent);
            margin-bottom: 0.75rem;
            display: block;
        }

        .dropzone-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
    </style>

    <script>
        // Dropzone functionality
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file');
        const uploadPreview = document.getElementById('upload-preview');
        const previewName = document.getElementById('preview-name');
        const previewIcon = document.getElementById('preview-icon');
        const uploadBtn = document.getElementById('upload-btn');

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                showPreview(e.dataTransfer.files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                showPreview(e.target.files[0]);
            }
        });

        function showPreview(file) {
            previewName.textContent = file.name;
            uploadPreview.classList.remove('hidden');
            uploadBtn.disabled = false;

            const ext = file.name.split('.').pop().toLowerCase();
            const iconMap = {
                'jpg': 'fa-image', 'jpeg': 'fa-image', 'png': 'fa-image', 'gif': 'fa-image', 'webp': 'fa-image',
                'mp4': 'fa-video', 'webm': 'fa-video',
                'mp3': 'fa-music',
                'pdf': 'fa-file-pdf'
            };
            previewIcon.className = 'fas ' + (iconMap[ext] || 'fa-file');
        }

        function clearUpload() {
            fileInput.value = '';
            uploadPreview.classList.add('hidden');
            uploadBtn.disabled = true;
        }

        // Upload form
        document.getElementById('upload-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';

            try {
                const response = await fetch('/api/media', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    toastr.success('Media uploaded successfully!');
                    document.getElementById('upload-modal').classList.add('hidden');
                    location.reload();
                } else {
                    toastr.error(data.message || 'Upload failed');
                }
            } catch (error) {
                toastr.error('Upload failed: ' + error.message);
            } finally {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload';
            }
        });

        // Delete media
        async function deleteMedia(id) {
            if (!confirm('Are you sure you want to delete this media?')) return;

            try {
                const response = await fetch(`/api/media/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    toastr.success('Media deleted successfully!');
                    document.querySelector(`[data-id="${id}"]`).remove();
                } else {
                    toastr.error(data.message || 'Delete failed');
                }
            } catch (error) {
                toastr.error('Delete failed: ' + error.message);
            }
        }

        // Copy to clipboard
        function copyToClipboard(url) {
            navigator.clipboard.writeText(url).then(() => {
                toastr.success('URL copied to clipboard!');
            }).catch(() => {
                toastr.error('Failed to copy URL');
            });
        }

        // Search and filter
        const searchInput = document.getElementById('search-media');
        const filterType = document.getElementById('filter-type');

        function filterMedia() {
            const search = searchInput.value.toLowerCase();
            const type = filterType.value;

            document.querySelectorAll('.media-item').forEach(item => {
                const name = item.querySelector('.media-name').textContent.toLowerCase();
                const itemType = item.dataset.type;

                const matchesSearch = name.includes(search);
                const matchesType = !type || itemType === type;

                item.style.display = matchesSearch && matchesType ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterMedia);
        filterType.addEventListener('change', filterMedia);
    </script>
</x-admin.layouts.app>
