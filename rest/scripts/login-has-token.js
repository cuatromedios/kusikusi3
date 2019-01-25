client.test("Login has token", function() {
  client.assert(response.body.data.token, "Response has a token");
  client.global.set("token", response.body.data.token)
});