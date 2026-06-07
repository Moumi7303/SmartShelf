<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.books.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                {{ isset($book) ? 'Edit Book' : 'Add New Book' }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="glass-card">
            <form action="{{ isset($book) ? route('admin.books.update', $book) : route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                @if(isset($book))
                    @method('PUT')
                @endif

                <!-- Cover Image Preview Area -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 border-b border-slate-200 dark:border-slate-700 pb-8">
                    <div class="w-32 h-48 bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm flex-shrink-0" id="imagePreviewContainer">
                        @if(isset($book) && $book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}" alt="Cover" class="w-full h-full object-cover" id="imagePreview">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400" id="imagePlaceholder">
                                <svg class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>
                                <span class="text-[10px] font-medium uppercase tracking-widest text-center px-2">Upload<br>Cover</span>
                            </div>
                            <img src="" alt="Cover" class="w-full h-full object-cover hidden" id="imagePreview">
                        @endif
                    </div>
                    
                    <div class="flex-1 w-full">
                        <label for="cover_image_file" class="label-field">Book Cover (Optional)</label>
                        <input type="file" name="cover_image_file" id="cover_image_file" accept="image/*" class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand hover:file:bg-brand-100 dark:file:bg-brand-900/30 dark:file:text-brand-300 transition-colors cursor-pointer" onchange="previewImage(event)">
                        <p class="mt-2 text-xs text-slate-500">Supported formats: JPG, PNG, WEBP. Max size: 2MB. Recommended aspect ratio: 2:3.</p>
                        <x-input-error :messages="$errors->get('cover_image_file')" class="mt-2" />
                    </div>
                </div>

                <!-- Basic Details -->
                <div>
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4">Basic Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="title" class="label-field">Book Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title ?? '') }}" required class="input-field" placeholder="Enter full book title">
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        
                        <div>
                            <label for="isbn" class="label-field">ISBN</label>
                            <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" class="input-field font-mono" placeholder="e.g. 978-3-16-148410-0">
                            <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
                        </div>
                        
                        <div>
                            <label for="status" class="label-field">Initial Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required class="input-field">
                                <option value="available" {{ old('status', $book->status ?? '') == 'available' ? 'selected' : '' }}>Available in Catalog</option>
                                <option value="unavailable" {{ old('status', $book->status ?? '') == 'unavailable' ? 'selected' : '' }}>Unavailable / Hidden</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Classification -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4">Classification & Attribution</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="category_id" class="label-field">Category <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" required class="input-field">
                                <option value="">Select Category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $book->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <label for="author_id" class="label-field">Author <span class="text-red-500">*</span></label>
                            <select name="author_id" id="author_id" required class="input-field">
                                <option value="">Select Author...</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ old('author_id', $book->author_id ?? '') == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('author_id')" class="mt-2" />
                        </div>

                        <div>
                            <label for="publisher_id" class="label-field">Publisher</label>
                            <select name="publisher_id" id="publisher_id" class="input-field">
                                <option value="">Unknown / N/A</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}" {{ old('publisher_id', $book->publisher_id ?? '') == $publisher->id ? 'selected' : '' }}>{{ $publisher->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('publisher_id')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Publication Data -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4">Publication & Location</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label for="publication_year" class="label-field">Year</label>
                            <input type="number" name="publication_year" id="publication_year" value="{{ old('publication_year', $book->publication_year ?? '') }}" min="1000" max="{{ date('Y') + 1 }}" class="input-field" placeholder="YYYY">
                            <x-input-error :messages="$errors->get('publication_year')" class="mt-2" />
                        </div>
                        
                        <div>
                            <label for="edition" class="label-field">Edition</label>
                            <input type="text" name="edition" id="edition" value="{{ old('edition', $book->edition ?? '') }}" class="input-field" placeholder="e.g. 2nd Edition">
                            <x-input-error :messages="$errors->get('edition')" class="mt-2" />
                        </div>
                        
                        <div>
                            <label for="language" class="label-field">Language</label>
                            <input type="text" name="language" id="language" value="{{ old('language', $book->language ?? 'English') }}" class="input-field">
                            <x-input-error :messages="$errors->get('language')" class="mt-2" />
                        </div>
                        
                        <div>
                            <label for="shelf_location" class="label-field">Default Shelf</label>
                            <input type="text" name="shelf_location" id="shelf_location" value="{{ old('shelf_location', $book->shelf_location ?? '') }}" class="input-field" placeholder="e.g. A3-R2">
                            <x-input-error :messages="$errors->get('shelf_location')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <label for="description" class="label-field">Synopsis / Description</label>
                    <textarea name="description" id="description" rows="5" class="input-field resize-y">{{ old('description', $book->description ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-4">
                    <a href="{{ route('admin.books.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        {{ isset($book) ? 'Update Book' : 'Save Book to Catalog' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Preview Script -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    const placeholder = document.getElementById('imagePlaceholder');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
