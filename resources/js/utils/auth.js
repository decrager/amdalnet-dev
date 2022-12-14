import Cookies from 'js-cookie';

const TokenKey = 'isLogged';
const IsOSSKey = 'isOSS';

export function isLogged() {
  return Cookies.get(TokenKey) === '1';
}

export function setLogged(isLogged) {
  return Cookies.set(TokenKey, isLogged);
}

export function isOSS() {
  return Cookies.get(IsOSSKey) === '1';
}

export function setOSS(isOSS) {
  return Cookies.set(IsOSSKey, isOSS);
}

export function removeToken() {
  return Cookies.remove(TokenKey);
}

export function removeIsOSS() {
  return Cookies.remove(IsOSSKey);
}
