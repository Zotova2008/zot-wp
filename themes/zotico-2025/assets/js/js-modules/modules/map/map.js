
export const initMap = function () {
  const mapBodyText = document.querySelector('[data-map="map-body-text"]');
  const dataCities = '../data/data-map.json';

  let dataMap;
  let nameCity;
  let positionX;
  let positionY;
  let size;

  const getData = async (url) => {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Ошибка по адресу ${url}, статус ошибки ${response.status}`);
    }
    return await response.json();
  };

  const templateMapText = function (box, map, city, x, y, z) {
    const span = document.createElement('span');
    span.className = `map__city map__city--${z}`;
    span.setAttribute('data-map', map);
    span.innerText = city;
    span.setAttribute('style', `left: ${x}%; top: ${y}%`);
    box.append(span);
  };

  getData(dataCities).then((data) => {
    data.forEach((element) => {
      dataMap = element['dataMap'];

      element['city'].forEach((item) => {
        nameCity = item['nameCity'];
        positionX = item['positionX'];
        positionY = item['positionY'];
        size = item['size'];
        templateMapText(mapBodyText, dataMap, nameCity, positionX, positionY, size);
      });

    });
  });

  // РАБОТАЕТ
  //   fetch('../data/dataMap.json')
  //     .then((response) => response.json())
  //     .then((result) => {
  //       mapBodyText.innerHTML = JSON.stringify(result);
  //     });

  // РАБОТАЕТ END

  // РАБОТАЕТ
  // исходные данные в виде строки JSON
  // const data = '../data/data-map.json';
  // парсим данные
  // const parsedData = JSON.parse(data);
  // console.log(parsedData.users[1].name); // => Bob
  // console.log(typeof exampleJsonFile); // => object
  // console.log(exampleJsonFile.cities); // => {debug: 'on', window: {…}}
  // console.log(exampleJsonFile.cities[1].city[1]['positionX']); // => Sample Konfabulator Widget
  // РАБОТАЕТ END
};
