<x-layouts.app>
    <x-slot name="title">Upload Content - Media Hub</x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 50%, #ec4899 100%); padding: 2rem; margin-bottom: 2rem; border-radius: 12px;">
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: white; font-weight: 700; text-align: center;">
                <i class="fas fa-cloud-upload-alt" style="margin-right: 0.5rem;"></i>
                Upload Your Content
            </h1>
            <p style="color: rgba(255,255,255,0.9); text-align: center;">
                Share your mods, maps, and creations with the community
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Please fix the following errors:</strong>
                <ul class="mt-2 ml-4 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-bold mb-2" style="color: var(--color-text-primary);">
                        <i class="fas fa-heading" style="color: var(--color-accent);"></i> Title *
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}"
                           required
                           maxlength="255"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="Enter a descriptive title for your content">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <label for="media_category_id" class="block text-sm font-bold mb-2" style="color: var(--color-text-primary);">
                        <i class="fas fa-folder" style="color: var(--color-accent);"></i> Game Category *
                    </label>
                    <select name="media_category_id" 
                            id="media_category_id" 
                            required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Select a game...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('media_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('media_category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subcategory -->
                <div class="mb-6" id="subcategory-container" style="display: none;">
                    <label for="media_subcategory_id" class="block text-sm font-bold mb-2" style="color: var(--color-text-primary);">
                        <i class="fas fa-layer-group" style="color: var(--color-accent);"></i> Content Type *
                    </label>
                    <select name="media_subcategory_id" 
                            id="media_subcategory_id" 
                            required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Select content type...</option>
                    </select>
                    @error('media_subcategory_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-bold mb-2" style="color: var(--color-text-primary);">
                        <i class="fas fa-align-left" style="color: var(--color-accent);"></i> Description *
                    </label>
                    <textarea name="description" 
                              id="description" 
                              required
                              maxlength="1000"
                              rows="4"
                              class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Describe your content in detail (max 1000 characters)">{{ old('description') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">
                        <span id="char-count">0</span>/1000 characters
                    </p>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content/Instructions -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-bold mb-2" style="color: var(--color-text-primary);">
                        <i class="fas fa-file-alt" style="color: var(--color-accent);"></i> Installation Instructions (Optional)
                    </label>
                    <textarea name="content" 
                              id="content" 
                              rows="6"
                              class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Provide installation instructions, requirements, or additional details...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Version -->
                <div class="mb-6">
                    <label for="version" class="block text-sm font-bold mb-2" style="color: var(--color-text-primary);">
                        <i class="fas fa-code-branch" style="color: var(--color-accent);"></i> Version (Optional)
                    </label>
                    <input type="text" 
                           name="version" 
                           id="version" 
                           value="{{ old('version') }}"
                           maxlength="50"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="e.g., 1.0.0, v2.5, Beta">
                    @error('version')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label for="file" class="block text-sm font-bold mb-2" style="color: var(--color-text-primary);">
                        <i class="fas fa-file-archive" style="color: var(--color-accent);"></i> File *
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer"
                         id="file-dropzone"
                         onclick="document.getElementById('file').click()">
                        <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                            Click to upload or drag and drop
                        </p>
                        <p class="text-sm text-gray-500">
                            ZIP, RAR, 7Z, TAR, GZ (Max: 100MB)
                        </p>
                        <input type="file" 
                               name="file" 
                               id="file" 
                               required
                               accept=".zip,.rar,.7z,.tar,.gz"
                               class="hidden">
                    </div>
                    <div id="file-preview" class="mt-3 hidden">
                        <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file-archive text-blue-600 dark:text-blue-400 text-xl mr-3"></i>
                                <span id="file-name" class="text-sm font-medium"></span>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-bold mb-2" style="color: var(--color-text-primary);">
                        <i class="fas fa-image" style="color: var(--color-accent);"></i> Preview Image (Optional)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer"
                         id="image-dropzone"
                         onclick="document.getElementById('image').click()">
                        <i class="fas fa-image text-5xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                            Click to upload or drag and drop
                        </p>
                        <p class="text-sm text-gray-500">
                            PNG, JPG, GIF, WebP (Max: 5MB)
                        </p>
                        <input type="file" 
                               name="image" 
                               id="image" 
                               accept="image/png,image/jpeg,image/gif,image/webp"
                               class="hidden">
                    </div>
                    <div id="image-preview" class="mt-3 hidden">
                        <img id="image-preview-img" src="" alt="Preview" class="max-w-full h-auto rounded-lg">
                    </div>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
                    <h3 class="font-bold text-blue-900 dark:text-blue-200 mb-2">
                        <i class="fas fa-info-circle"></i> Before You Submit
                    </h3>
                    <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-1 ml-5 list-disc">
                        <li>Your submission will be reviewed by our team before going live</li>
                        <li>Make sure your content follows our community guidelines</li>
                        <li>Provide clear installation instructions for best results</li>
                        <li>High-quality preview images improve visibility</li>
                    </ul>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105">
                        <i class="fas fa-upload"></i> Submit for Review
                    </button>
                    <a href="{{ route('media.index') }}" 
                       class="px-6 py-3 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg font-bold transition-colors"
                       style="color: var(--color-text-primary);">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Subcategories data
        const subcategories = @json($categories->mapWithKeys(function($category) {
            return [$category->id => $category->subcategories];
        }));

        // Category change handler
        document.getElementById('media_category_id').addEventListener('change', function() {
            const categoryId = this.value;
            const subcategorySelect = document.getElementById('media_subcategory_id');
            const subcategoryContainer = document.getElementById('subcategory-container');
            
            subcategorySelect.innerHTML = '<option value="">Select content type...</option>';
            
            if (categoryId && subcategories[categoryId]) {
                subcategories[categoryId].forEach(function(subcategory) {
                    const option = document.createElement('option');
                    option.value = subcategory.id;
                    option.textContent = subcategory.name;
                    subcategorySelect.appendChild(option);
                });
                subcategoryContainer.style.display = 'block';
            } else {
                subcategoryContainer.style.display = 'none';
            }
        });

        // Character counter
        const descriptionField = document.getElementById('description');
        const charCount = document.getElementById('char-count');
        descriptionField.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // File upload handlers
        const fileInput = document.getElementById('file');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
                filePreview.classList.remove('hidden');
            }
        });

        function clearFile() {
            fileInput.value = '';
            filePreview.classList.add('hidden');
        }

        // Image upload handlers
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const imagePreviewImg = document.getElementById('image-preview-img');

        imageInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreviewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Drag and drop
        ['file-dropzone', 'image-dropzone'].forEach(function(id) {
            const dropzone = document.getElementById(id);
            const input = id === 'file-dropzone' ? fileInput : imageInput;

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.add('border-blue-500');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.remove('border-blue-500');
                }, false);
            });

            dropzone.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                input.files = files;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            }, false);
        });
    </script>
    @endpush
</x-layouts.app>
