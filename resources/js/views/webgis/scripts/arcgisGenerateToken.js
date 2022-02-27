import qs from 'qs';
import * as urlUtils from '@arcgis/core/core/urlUtils';
import esriRequest from '@arcgis/core/request';

function generateArcgisToken(setToken) {
  const dateNow = new Date();

  if (localStorage.getItem('arcToken') === null && localStorage.getItem('arcExpired') === null) {
    if (localStorage.getItem('arcExpired') < dateNow.getTime() / 1000) {
      urlUtils.addProxyRule({
        proxyUrl: 'proxy/proxy.php',
        urlPrefix: 'https://amdalgis.menlhk.go.id/',
      });

      const data = qs.stringify({
        'username': 'Amdalnet',
        'password': 'Amdalnet123',
        'client': 'requestip',
        'expiration': 20160,
        'f': 'json',
      });

      const myHeaders = new Headers();
      myHeaders.append('Content-Type', 'application/x-www-form-urlencoded');

      const requestOptions = {
        method: 'post',
        headers: myHeaders,
        body: data,
      };

      esriRequest('https://amdalgis.menlhk.go.id/portal/sharing/rest/generateToken', requestOptions)
        .then((response) => {
          setToken = response.data.token;
          localStorage.setItem('arcToken', response.data.token);
          localStorage.setItem('arcExpired', response.data.expires);
        });
    }
  }
}

export default generateArcgisToken;

