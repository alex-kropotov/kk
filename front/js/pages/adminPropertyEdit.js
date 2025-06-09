// Пример данных городов (можно заменить на загрузку с сервера)
import Autocomplete from "@common/autocomplete.js";

document.addEventListener('DOMContentLoaded', () => {

    const CityList = new Autocomplete({
        inputSelector: '#citySearch',
        minChars: 0,
        loadingText: 'Ищем города...',
        placeholder: 'Type city name...',
        noResultsText: 'No cities found',
        searchFields: ['name'], // Поиск по названию и стране
        displayField: 'name', // Отображаем название города
        allowCreateNew: false, // Запрещаем создание новых городов

        onLoadData: async (query) => {
            // if (query.length < 2) return [];

            try {
                const response = await fetch('/api/admin/citySearch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({searchTemplate: query}),
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const data = await response.json();
                console.log(data);
                return data.data.map(item => ({
                    id: item.idPlace,
                    name: item.namePlaceRs,
                }));
            } catch (error) {
                return [];
            }
        },

        templates: {
            item: (item) => `
            <div class="osm-result">
                <div class="osm-name">${item.name.split(',')[0]}</div>
                <div class="osm-address">${item.name.split(',').slice(1).join(',')}</div>
            </div>
        `
        },

        onSelect: (city) => {
            alert('Выбран город: ' + city.name);
        }
    });

    CityList.loadData();
});
