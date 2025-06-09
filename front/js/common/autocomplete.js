/**
 * Класс для создания компонента автозаполнения с поддержкой:
 * - Локального поиска по массиву данных
 * - Асинхронной загрузки данных
 * - Кастомных шаблонов отображения
 * - Различных колбэков для обработки событий
 *
 * @example
 * // Простейшая инициализация
 * const autocomplete = new Autocomplete({
 *   inputSelector: '#myInput',
 *   data: [{id: 1, name: 'Item 1'}, {id: 2, name: 'Item 2'}]
 * });
 *
 * @example
 * // С асинхронной загрузкой данных
 * const autocomplete = new Autocomplete({
 *   inputSelector: '#searchInput',
 *   minChars: 3,
 *   onLoadData: async (query) => {
 *     const response = await fetch(`/api/search?q=${query}`);
 *     return await response.json();
 *   }
 * });
 */
export class Autocomplete {
    /**
     * Создает экземпляр Autocomplete
     * @param {Object} options - Настройки автозаполнения
     * @param {string|HTMLElement} options.inputSelector - Селектор CSS или DOM-элемент input
     * @param {Array} [options.data=[]] - Начальный набор данных для поиска
     * @param {number} [options.minChars=3] - Минимальное кол-во символов для активации поиска
     * @param {string} [options.placeholder='Начните вводить...'] - Текст placeholder
     * @param {string} [options.noResultsText='Ничего не найдено'] - Текст при отсутствии результатов
     * @param {string} [options.loadingText='Loading...'] - Текст во время загрузки данных
     * @param {string} [options.displayField='name'] - Поле элемента для отображения
     * @param {string[]} [options.searchFields=['name']] - Поля для поиска
     * @param {boolean} [options.allowCreateNew=true] - Разрешить создание новых элементов
     *
     * @param {Function} [options.onSelect] - Колбэк при выборе элемента
     * @param {Function} [options.onCreateNew] - Колбэк при создании нового элемента
     * @param {Function} [options.onBlurWithValue] - Колбэк при потере фокуса с введенным значением
     * @param {Function} [options.onLoadData] - Колбэк для асинхронной загрузки данных
     *
     * @param {Object} [options.templates] - Кастомные шаблоны отображения
     * @param {Function} [options.templates.item] - Шаблон для элемента списка
     * @param {Function} [options.templates.noResults] - Шаблон при отсутствии результатов
     *
     * @throws {Error} Если не указан inputSelector или data не является массивом
     */
    constructor(options) {
        // Проверка типов обязательных параметров
        if (!options) {
            throw new Error('Options object is required');
        }

        if (!options.inputSelector && !(options.inputSelector instanceof HTMLElement)) {
            throw new Error('inputSelector must be a string or HTMLElement');
        }

        this.options = {
            inputSelector: null,
            data: [],
            minChars: 3,
            placeholder: 'Начните вводить...',
            noResultsText: 'Ничего не найдено',
            onSelect: null,
            onCreateNew: null,
            allowCreateNew: true,
            searchFields: ['name'],
            displayField: 'name',
            onBlurWithValue: null,
            templates: null,
            onLoadData: null,
            loadingText: 'Loading...',
            ...options
        };

        // Инициализация элемента ввода
        this.input = typeof this.options.inputSelector === 'string'
            ? document.querySelector(this.options.inputSelector)
            : this.options.inputSelector;

        if (!this.input) {
            throw new Error('Input element not found');
        }

        this.container = null;
        this.dropdown = null;
        this.filteredData = [];
        this.activeIndex = -1;
        this.selectedItem = null;
        this.isManualInput = false;
        this.lastRequestId = 0;
        this.initialDataLoaded = false; // Флаг, указывающий, что данные были загружены
        this.currentQuery = ''; // Текущий поисковый запрос

        this.init();
    }

    /**
     * Публичный метод для ручной загрузки данных с текущим значением поля ввода
     * После вызова этого метода автоматическая загрузка при фокусе не происходит
     * @returns {Promise<void>}
     */
    async loadData() {
        const query = this.input.value.trim();

        if (typeof this.options.onLoadData === 'function') {
            try {
                // this.showLoading();
                const newData = await this.options.onLoadData(query);
                this.options.data = Array.isArray(newData) ? newData : [];
                this.initialDataLoaded = true;

                // Показываем выпадающий список только если есть введенные символы
                if (query.length > 0) {
                    this.filterData(query);
                    this.showDropdown();
                } else {
                    this.hideDropdown();
                }
            } catch (error) {
                console.error('Error loading data:', error);
                this.hideDropdown();
            }
        } else {
            this.initialDataLoaded = true;
            // Для локальных данных просто фильтруем
            if (query.length > 0) {
                this.filterData(query);
                this.showDropdown();
            } else {
                this.hideDropdown();
            }
        }
    }

    init() {
        this.createContainer();
        this.attachEvents();

        // Установка placeholder
        if (this.options.placeholder) {
            this.input.placeholder = this.options.placeholder;
        }
    }

    createContainer() {
        // Оборачиваем input в контейнер если он не обернут
        if (!this.input.parentElement.classList.contains('autocomplete-container')) {
            const container = document.createElement('div');
            container.className = 'autocomplete-container';
            this.input.parentNode.insertBefore(container, this.input);
            container.appendChild(this.input);
        }

        this.container = this.input.parentElement;

        // Создаем dropdown
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'autocomplete-dropdown';
        this.dropdown.style.display = 'none';
        this.container.appendChild(this.dropdown);
    }

    /**
     * Асинхронно загружает данные через колбэк onLoadData
     * @param {string} query - Поисковый запрос
     * @returns {Promise<void>}
     * @private
     */
    async loadExternalData(query) {
        if (typeof this.options.onLoadData !== 'function') return;

        const requestId = ++this.lastRequestId;

        try {
            // this.showLoading();
            const newData = await this.options.onLoadData(query);

            // Проверяем что это последний запрос
            if (requestId === this.lastRequestId && query === this.input.value.trim()) {
                this.options.data = Array.isArray(newData) ? newData : [];
                this.initialDataLoaded = true; // Помечаем, что данные загружены
                this.currentQuery = query; // Сохраняем текущий запрос
                this.filterData(query);
                this.showDropdown();
            }
        } catch (error) {
            if (requestId === this.lastRequestId) {
                console.error('Error loading data:', error);
                this.hideDropdown();
            }
        }
    }

    /**
     * Показывает индикатор загрузки
     * @private
     */
    showLoading() {
        this.dropdown.innerHTML = `<div class="autocomplete-loading">${this.options.loadingText}</div>`;
        this.dropdown.style.display = 'block';
    }

    debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    attachEvents() {
        this.input.addEventListener('input', this.handleInput.bind(this));
        this.input.addEventListener('keydown', this.handleKeydown.bind(this));
        this.input.addEventListener('blur', this.handleBlur.bind(this));
        this.input.addEventListener('focus', this.handleFocus.bind(this));

        // Закрытие при клике вне элемента
        document.addEventListener('click', (e) => {
            if (!this.container.contains(e.target)) {
                this.hideDropdown();
            }
        });
    }

    handleInput() {
        const query = this.input.value.trim();
        this.isManualInput = true;
        this.selectedItem = null;

        // Не показывать выпадающий список при пустом вводе, даже если minChars = 0
        if (query.length === 0) {
            this.hideDropdown();
            return;
        }

        // Особый случай: если minChars = 0, но есть введенные символы
        if (this.options.minChars === 0) {
            // Если данные уже были загружены - просто фильтруем
            if (this.initialDataLoaded) {
                this.filterData(query);
                this.showDropdown();
            }
            // Если есть колбэк для загрузки и данные еще не загружались - загружаем
            else if (typeof this.options.onLoadData === 'function') {
                this.loadExternalData(query);
            } else {
                // Иначе используем стандартную фильтрацию
                this.filterData(query);
                this.showDropdown();
            }
            return;
        }

        // Стандартная логика для minChars > 0
        if (query.length < this.options.minChars) {
            this.resetData();
            this.hideDropdown();
            return;
        }

        if (this.initialDataLoaded) {
            this.filterData(query);
            this.showDropdown();
        }
        else if (typeof this.options.onLoadData === 'function') {
            this.loadExternalData(query);
        } else {
            this.filterData(query);
            this.showDropdown();
        }
    }

    /**
     * Сбрасывает загруженные данные
     * @private
     */
    resetData() {
        this.options.data = [];
        this.filteredData = [];
        this.initialDataLoaded = false;
        this.currentQuery = '';
    }

    handleKeydown(e) {
        if (!this.isDropdownVisible()) return;

        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.navigateDown();
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.navigateUp();
                break;
            case 'Enter':
                e.preventDefault();
                this.selectActive();
                break;
            case 'Escape':
                this.hideDropdown();
                break;
            case 'Backspace':
                if (this.input.value.length < this.options.minChars) {
                    this.resetData();
                }
                break;
        }
    }

    /**
     * Обработчик потери фокуса
     * @private
     */
    handleBlur() {
        setTimeout(() => {
            if (!this.dropdown.matches(':hover')) {
                this.hideDropdown();

                const currentValue = this.input.value.trim();

                if (currentValue && !this.selectedItem) {
                    // Колбэк для создания нового элемента (если разрешено)
                    if (this.options.allowCreateNew && typeof this.options.onCreateNew === 'function') {
                        this.options.onCreateNew(currentValue, this.input);
                    }

                    // Колбэк для любого введённого значения
                    if (typeof this.options.onBlurWithValue === 'function') {
                        this.options.onBlurWithValue(currentValue, this.input);
                    }
                }
            }
        }, 150);
    }

    handleFocus() {
        // Если данные уже загружены вручную через loadData() - пропускаем автоматическую загрузку
        if (this.initialDataLoaded) {
            const query = this.input.value.trim();
            if (query.length >= this.options.minChars) {
                this.filterData(query);
                this.showDropdown();
            }
            return;
        }

        const query = this.input.value.trim();

        // Если minChars = 0, загружаем данные при фокусе (в фоне), но не показываем список
        if (this.options.minChars === 0 && !this.initialDataLoaded) {
            if (typeof this.options.onLoadData === 'function') {
                this.loadExternalData(''); // Загружаем все данные
            }
            return;
        }

        // Стандартная логика для minChars > 0
        if (query.length >= this.options.minChars) {
            if (this.initialDataLoaded) {
                this.filterData(query);
                this.showDropdown();
            }
            else if (typeof this.options.onLoadData === 'function') {
                this.loadExternalData(query);
            } else {
                this.filterData(query);
                this.showDropdown();
            }
        }
    }

    filterData(query) {
        const lowerQuery = query.toLowerCase();
        this.filteredData = this.options.data.filter(item => {
            return this.options.searchFields.some(field => {
                const fieldValue = this.getNestedValue(item, field);
                return fieldValue && fieldValue.toString().toLowerCase().includes(lowerQuery);
            });
        });
        this.activeIndex = -1;
    }

    /**
     * Получает значение из объекта по пути (включая вложенные свойства)
     * @param {Object} obj - Исходный объект
     * @param {string} path - Путь к свойству (например, 'address.city')
     * @returns {*} Значение свойства или undefined
     * @private
     */
    getNestedValue(obj, path) {
        if (!obj || typeof obj !== 'object' || !path) {
            return undefined;
        }

        try {
            return path.split('.').reduce((current, key) => {
                if (current && typeof current === 'object' && key in current) {
                    return current[key];
                }
                return undefined;
            }, obj);
        } catch (error) {
            console.error('Error getting nested value:', error);
            return undefined;
        }
    }

    showDropdown() {
        if (this.filteredData.length === 0) {
            this.dropdown.innerHTML = `<div class="autocomplete-no-results">${this.options.noResultsText}</div>`;
        } else {
            this.dropdown.innerHTML = this.filteredData
                .map((item, index) => {
                    const displayText = this.getNestedValue(item, this.options.displayField);
                    return `<div class="autocomplete-item" data-index="${index}" data-id="${item.id}" ${this.getDataAttributes(item)}>
                        ${this.highlightMatch(displayText)}
                    </div>`;
                }).join('');
        }

        // Добавляем обработчики кликов
        this.dropdown.querySelectorAll('.autocomplete-item').forEach(item => {
            item.addEventListener('click', () => {
                const index = parseInt(item.dataset.index);
                this.selectItem(this.filteredData[index]);
            });
        });

        this.dropdown.style.display = 'block';
    }

    /**
     * Генерирует data-атрибуты из свойств объекта
     * @param {Object} item - Элемент данных
     * @returns {string} Строка с data-атрибутами
     * @private
     */
    getDataAttributes(item) {
        if (!item || typeof item !== 'object') {
            return '';
        }

        // Оптимизация: собираем только свойства, начинающиеся с data-
        const dataKeys = Object.keys(item).filter(key =>
            key.startsWith('data-') || key.startsWith('data_')
        );

        return dataKeys.map(key => {
            const attrName = key.replace('data_', 'data-');
            return `${attrName}="${item[key]}"`;
        }).join(' ');
    }

    hideDropdown() {
        this.dropdown.style.display = 'none';
        this.activeIndex = -1;
    }

    isDropdownVisible() {
        return this.dropdown.style.display === 'block';
    }

    navigateDown() {
        this.activeIndex = Math.min(this.activeIndex + 1, this.filteredData.length - 1);
        this.updateActiveItem();
    }

    navigateUp() {
        this.activeIndex = Math.max(this.activeIndex - 1, -1);
        this.updateActiveItem();
    }

    updateActiveItem() {
        this.dropdown.querySelectorAll('.autocomplete-item').forEach((item, index) => {
            item.classList.toggle('active', index === this.activeIndex);
        });

        if (this.activeIndex >= 0) {
            const activeItem = this.dropdown.children[this.activeIndex];
            activeItem.scrollIntoView({ block: 'nearest' });
        }
    }

    selectActive() {
        if (this.activeIndex >= 0 && this.activeIndex < this.filteredData.length) {
            this.selectItem(this.filteredData[this.activeIndex]);
        }
    }

    selectItem(item) {
        this.selectedItem = item;
        this.isManualInput = false;
        this.input.value = this.getNestedValue(item, this.options.displayField);
        this.hideDropdown();
        this.input.focus();

        // Устанавливаем data-атрибуты на input
        this.setInputDataAttributes(item);

        if (this.options.onSelect) {
            this.options.onSelect(item, this.input);
        }

        // Создаем кастомное событие
        this.input.dispatchEvent(new CustomEvent('autocomplete:select', {
            detail: { item: item, input: this.input }
        }));
    }

    setInputDataAttributes(item) {
        // Очищаем старые data-атрибуты
        Array.from(this.input.attributes).forEach(attr => {
            if (attr.name.startsWith('data-autocomplete-')) {
                this.input.removeAttribute(attr.name);
            }
        });

        // Устанавливаем новые
        this.input.setAttribute('data-autocomplete-id', item.id);
        Object.keys(item).forEach(key => {
            if (key.startsWith('data-') || key.startsWith('data_')) {
                const attrName = `data-autocomplete-${key.replace('data-', '').replace('data_', '')}`;
                this.input.setAttribute(attrName, item[key]);
            }
        });
    }

    highlightMatch(text) {
        const query = this.input.value.trim();
        if (!query) return this.escapeHtml(text);

        const regex = new RegExp(`(${this.escapeRegex(query)})`, 'gi');
        return this.escapeHtml(text).replace(regex, '<strong>$1</strong>');
    }

    // Добавить новый метод для экранирования HTML
    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Публичные методы
    setValue(item) {
        if (item) {
            this.selectItem(item);
        } else {
            this.input.value = '';
            this.selectedItem = null;
            this.isManualInput = false;
        }
    }

    getValue() {
        return this.selectedItem;
    }

    getInputValue() {
        return this.input.value;
    }

    updateData(newData) {
        this.options.data = newData;
        this.initialDataLoaded = true; // Помечаем, что данные загружены
    }

    destroy() {
        this.hideDropdown();
        if (this.dropdown && this.dropdown.parentNode) {
            this.dropdown.parentNode.removeChild(this.dropdown);
        }
    }
}

// Экспорт по умолчанию
export default Autocomplete;
