client.test('Login has token', function () {
  token = response.body.result.token;
  client.assert(token, 'Response has a token');
  client.global.set('token', token);
});
