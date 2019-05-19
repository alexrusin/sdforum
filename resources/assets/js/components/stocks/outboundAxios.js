const outboundAxios = axios;
delete outboundAxios.defaults.headers.common['X-CSRF-TOKEN'];

export default outboundAxios;