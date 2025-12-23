<div class=" px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">

    <form action="" class=" space-y-8">

        <div class="space-y-2">
            <label for="title" class="block text-sm font-montserrat font-medium text-primary">
                Título del Artículo
            </label>
            <input type="text" id="title" placeholder="Ingresa el titulo del artículo"
                class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
        </div>

        {{-- Mostrar Slug a tiempo real --}}

        <div class="space-y-2">
            <label for="subtitle" class="block text-sm font-montserrat font-medium text-primary">
                Subtitulo del Artículo
            </label>
            <input type="text" id="subtitle" placeholder="Ingresa el subtitulo del artículo"
                class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
        </div>

        {{-- Mostrar imagen seleccionada --}}
        <div class="space-y-2">
            <label for="image" class="block text-sm font-montserrat font-medium text-primary">
                Imagen Principal
            </label>
            <input type="file" id="image" accept=".webp,.jpeg,.jpg,.png,.gif"
                class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-dark-sage file:text-white hover:file:bg-primary">
            <p class="text-xs text-gray-500 mt-1">Tamaño máximo: 10MB. Formatos: JPG, PNG, WEBP</p>
        </div>

        <div class="space-y-2">
            <label for="section" class="block text-sm font-montserrat font-medium text-primary">
                Sección del Artículo
            </label>
            <select id="section"
                class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm appearance-none bg-no-repeat bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M1%201L6%206L11%201%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-position-[right_16px_center] transition-all duration-200 focus:bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M11%207L6%202L1%207%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')]">
                <option value="">-- Selecciona una sección --</option>
                <option value="destinations">Destinos</option>
                <option value="inspiring_stories">Historias que inspiran</option>
                <option value="social_events">Eventos Sociales</option>
                <option value="health_wellness">Salud y Bienestar</option>
                <option value="gastronomy">Gastronomía</option>
                <option value="living_culture">Cultura Viva</option>
            </select>
        </div>

        <div class="space-y-2">
            <label for="attribution" class="block text-sm font-montserrat font-medium text-primary">
                Créditos / Fuente
            </label>
            <input type="text" id="attribution" placeholder="Ej: Información cortesía de National Geographic"
                class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
        </div>

        <div class="space-y-2">
            <label for="summary" class="block text-sm font-montserrat font-medium text-primary">
                Resumen del Artículo
            </label>
            <textarea id="summary" rows="4"
                placeholder="Escribe un breve resumen del artículo para mostrar en las vistas previas..."
                class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm resize-none"></textarea>
        </div>

        <div class="space-y-2">
            <label for="visibility" class="block text-sm font-montserrat font-medium text-primary">
                Visibilidad
            </label>
            <select id="visibility"
                class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm appearance-none bg-no-repeat bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M1%201L6%206L11%201%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-position-[right_16px_center] transition-all duration-200 focus:bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M11%207L6%202L1%207%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')]">
                <option value="private">Privado (Solo administradores)</option>
                <option value="public">Público (Visible para todos)</option>
            </select>
        </div>

        <div class="space-y-2">
            <label for="published_at" class="block text-sm font-montserrat font-medium text-primary">
                Programar Publicación (Opcional)
            </label>
            <input type="datetime-local" id="published_at"
                class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
            <p class="text-xs text-gray-500 mt-1">Si no se selecciona, el artículo se publicará cuando cambie el estado
                a "publicado"</p>
        </div>

        <div class="space-y-2">
            <label for="tags" class="block text-sm font-montserrat font-medium text-primary">
                Tags / Etiquetas
            </label>
            <div class="relative">
                <input type="text" id="tags"
                    placeholder="Escribe para buscar tags... Ej: playa, aventura, gastronomía"
                    class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm"
                    autocomplete="off">

                <!-- Sugerencias simuladas (ocultas por defecto) -->
                <div id="tags-suggestions"
                    class="absolute top-full left-0 right-0 bg-white border border-gray-300 shadow-lg z-10 hidden">
                    <div class="p-2 hover:bg-gray-100 cursor-pointer text-sm">🏖️ playa</div>
                    <div class="p-2 hover:bg-gray-100 cursor-pointer text-sm">🍴 gastronomía</div>
                    <div class="p-2 hover:bg-gray-100 cursor-pointer text-sm">🏔️ aventura</div>
                    <div class="p-2 hover:bg-gray-100 cursor-pointer text-sm">🎭 cultura</div>
                    <div class="p-2 hover:bg-gray-100 cursor-pointer text-sm">🧘 bienestar</div>
                    <div class="p-2 hover:bg-gray-100 cursor-pointer text-sm">🎉 eventos</div>
                </div>
            </div>

            <!-- Tags seleccionados -->
            <div id="selected-tags" class="flex flex-wrap gap-2 mt-2">
                <!-- Los tags aparecerán aquí dinámicamente -->
            </div>

            <p class="text-xs text-gray-500">Escribe para buscar tags existentes o crear nuevos</p>
        </div>

        <div class="space-y-2">
            <label for="related-articles" class="block text-sm font-montserrat font-medium text-primary">
                Artículos Relacionados (Opcional)
            </label>
            <div class="relative">
                <input type="text" id="related-articles" placeholder="Busca artículos para relacionar..."
                    class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm"
                    autocomplete="off">

                <!-- Sugerencias de artículos simulados -->
                <div id="articles-suggestions"
                    class="absolute top-full left-0 right-0 bg-white border border-gray-300 shadow-lg z-10 hidden max-h-60 overflow-y-auto">
                    <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 article-option"
                        data-id="1" data-title="Fortaleza de San Fernando de Omoa: Guardiana del Caribe Hondureño">
                        <h4 class="text-sm font-medium text-primary">Fortaleza de San Fernando de Omoa</h4>
                        <p class="text-xs text-gray-500">Destinos • Ana Martínez</p>
                    </div>
                    <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 article-option"
                        data-id="2" data-title="Costa Brava: El Encanto Mediterráneo de Girona">
                        <h4 class="text-sm font-medium text-primary">Costa Brava: El Encanto Mediterráneo</h4>
                        <p class="text-xs text-gray-500">Destinos • María Fernández</p>
                    </div>
                    <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 article-option"
                        data-id="3" data-title="El Call de Girona: Un Viaje Medieval en el Tiempo">
                        <h4 class="text-sm font-medium text-primary">El Call de Girona: Un Viaje Medieval</h4>
                        <p class="text-xs text-gray-500">Cultura Viva • María Elena Vázquez</p>
                    </div>
                    <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 article-option"
                        data-id="4" data-title="Sabores de Honduras: Tradición en Cada Bocado">
                        <h4 class="text-sm font-medium text-primary">Sabores de Honduras: Tradición</h4>
                        <p class="text-xs text-gray-500">Gastronomía • Diego Hernández</p>
                    </div>
                    <div class="p-3 hover:bg-gray-100 cursor-pointer article-option" data-id="5"
                        data-title="Nueva York: Donde el Mundo se Encuentra en un Plato">
                        <h4 class="text-sm font-medium text-primary">Nueva York: Donde el Mundo se Encuentra</h4>
                        <p class="text-xs text-gray-500">Gastronomía • Diego Rodríguez</p>
                    </div>
                </div>
            </div>

            <!-- Artículos relacionados seleccionados -->
            <div id="selected-articles" class="space-y-2 mt-2">
                <!-- Los artículos aparecerán aquí dinámicamente -->
            </div>

            <p class="text-xs text-gray-500">Máximo 3 artículos relacionados recomendados</p>
        </div>

        <div class="space-y-2">
            <label for="reading_time" class="block text-sm font-montserrat font-medium text-primary">
                Tiempo de Lectura (Opcional)
            </label>
            <input type="number" id="reading_time" placeholder="Ej: 5" min="1" max="60"
                class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
            <p class="text-xs text-gray-500">Tiempo estimado que toma leer el artículo completo</p>
        </div>

    </form>

</div>

{{-- Cambiar en un futuro por los tags reales --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tagsInput = document.getElementById('tags');
        const tagsSuggestions = document.getElementById('tags-suggestions');
        const selectedTagsContainer = document.getElementById('selected-tags');

        const availableTags = ['playa', 'gastronomía', 'aventura', 'cultura', 'bienestar', 'eventos',
            'naturaleza', 'turismo', 'cocina', 'salud'
        ];
        let selectedTags = [];

        // Mostrar sugerencias al escribir
        tagsInput.addEventListener('input', function() {
            const value = this.value.toLowerCase().trim();

            if (value.length > 0) {
                const filteredTags = availableTags.filter(tag =>
                    tag.toLowerCase().includes(value) && !selectedTags.includes(tag)
                );

                if (filteredTags.length > 0) {
                    tagsSuggestions.innerHTML = filteredTags.map(tag =>
                        `<div class="p-2 hover:bg-gray-100 cursor-pointer text-sm tag-option" data-tag="${tag}">${tag}</div>`
                    ).join('');
                    tagsSuggestions.classList.remove('hidden');
                } else {
                    tagsSuggestions.classList.add('hidden');
                }
            } else {
                tagsSuggestions.classList.add('hidden');
            }
        });

        // Agregar tag al hacer clic en sugerencia
        tagsSuggestions.addEventListener('click', function(e) {
            if (e.target.classList.contains('tag-option')) {
                const tag = e.target.getAttribute('data-tag');
                addTag(tag);
                tagsInput.value = '';
                tagsSuggestions.classList.add('hidden');
            }
        });

        // Agregar tag con Enter
        tagsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const value = this.value.trim();
                if (value && !selectedTags.includes(value)) {
                    addTag(value);
                    this.value = '';
                    tagsSuggestions.classList.add('hidden');
                }
            }
        });

        // Ocultar sugerencias al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!tagsInput.contains(e.target) && !tagsSuggestions.contains(e.target)) {
                tagsSuggestions.classList.add('hidden');
            }
        });

        function addTag(tag) {
            if (!selectedTags.includes(tag)) {
                selectedTags.push(tag);
                renderTags();
            }
        }

        function removeTag(tag) {
            selectedTags = selectedTags.filter(t => t !== tag);
            renderTags();
        }

        function renderTags() {
            selectedTagsContainer.innerHTML = selectedTags.map(tag =>
                `<span class="inline-flex items-center px-3 py-1 bg-dark-sage text-white text-sm font-opensans">
                ${tag}
                <button type="button" class="ml-2 hover:text-gray-300" onclick="removeTag('${tag}')">&times;</button>
            </span>`
            ).join('');
        }

        // Hacer removeTag global para los botones
        window.removeTag = removeTag;

        // ===== ARTÍCULOS RELACIONADOS =====
        const articlesInput = document.getElementById('related-articles');
        const articlesSuggestions = document.getElementById('articles-suggestions');
        const selectedArticlesContainer = document.getElementById('selected-articles');

        const availableArticles = [{
                id: 1,
                title: 'Fortaleza de San Fernando de Omoa: Guardiana del Caribe Hondureño',
                section: 'Destinos',
                author: 'Ana Martínez'
            },
            {
                id: 2,
                title: 'Costa Brava: El Encanto Mediterráneo de Girona',
                section: 'Destinos',
                author: 'María Fernández'
            },
            {
                id: 3,
                title: 'El Call de Girona: Un Viaje Medieval en el Tiempo',
                section: 'Cultura Viva',
                author: 'María Elena Vázquez'
            },
            {
                id: 4,
                title: 'Sabores de Honduras: Tradición en Cada Bocado',
                section: 'Gastronomía',
                author: 'Diego Hernández'
            },
            {
                id: 5,
                title: 'Nueva York: Donde el Mundo se Encuentra en un Plato',
                section: 'Gastronomía',
                author: 'Diego Rodríguez'
            },
            {
                id: 6,
                title: 'Festivales que Celebran la Identidad Cultural',
                section: 'Eventos Sociales',
                author: 'Carmen Silva'
            },
            {
                id: 7,
                title: 'Encontrando el Equilibrio en Tiempos Acelerados',
                section: 'Salud y Bienestar',
                author: 'Carmen Silva'
            }
        ];
        let selectedArticles = [];

        // Mostrar sugerencias de artículos al escribir
        articlesInput.addEventListener('input', function() {
            const value = this.value.toLowerCase().trim();

            if (value.length > 1) {
                const filteredArticles = availableArticles.filter(article =>
                    (article.title.toLowerCase().includes(value) ||
                        article.section.toLowerCase().includes(value) ||
                        article.author.toLowerCase().includes(value)) &&
                    !selectedArticles.find(selected => selected.id === article.id)
                );

                if (filteredArticles.length > 0) {
                    articlesSuggestions.innerHTML = filteredArticles.map(article =>
                        `<div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 article-option" data-id="${article.id}">
                        <h4 class="text-sm font-medium text-primary">${article.title.substring(0, 50)}${article.title.length > 50 ? '...' : ''}</h4>
                        <p class="text-xs text-gray-500">${article.section} • ${article.author}</p>
                    </div>`
                    ).join('');
                    articlesSuggestions.classList.remove('hidden');
                } else {
                    articlesSuggestions.classList.add('hidden');
                }
            } else {
                articlesSuggestions.classList.add('hidden');
            }
        });

        // Agregar artículo al hacer clic en sugerencia
        articlesSuggestions.addEventListener('click', function(e) {
            const articleElement = e.target.closest('.article-option');
            if (articleElement && selectedArticles.length < 3) {
                const articleId = parseInt(articleElement.getAttribute('data-id'));
                const article = availableArticles.find(a => a.id === articleId);
                if (article) {
                    addArticle(article);
                    articlesInput.value = '';
                    articlesSuggestions.classList.add('hidden');
                }
            }
        });

        // Ocultar sugerencias de artículos al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!articlesInput.contains(e.target) && !articlesSuggestions.contains(e.target)) {
                articlesSuggestions.classList.add('hidden');
            }
        });

        function addArticle(article) {
            if (!selectedArticles.find(selected => selected.id === article.id) && selectedArticles.length < 3) {
                selectedArticles.push(article);
                renderArticles();
            }
        }

        function removeArticle(articleId) {
            selectedArticles = selectedArticles.filter(article => article.id !== parseInt(articleId));
            renderArticles();
        }

        function renderArticles() {
            selectedArticlesContainer.innerHTML = selectedArticles.map(article =>
                `<div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200">
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-primary">${article.title}</h4>
                    <p class="text-xs text-gray-500">${article.section} • ${article.author}</p>
                </div>
                <button type="button" class="ml-3 text-gray-400 hover:text-red-500 text-lg" onclick="removeArticle(${article.id})">&times;</button>
            </div>`
            ).join('');
        }

        // Hacer removeArticle global para los botones
        window.removeArticle = removeArticle;
    });
</script>
