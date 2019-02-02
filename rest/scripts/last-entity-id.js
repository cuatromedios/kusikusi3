client.test("Login has token", function() {
  entity_id = response.body.result.id
  client.assert(entity_id, "Response has an id");
  client.global.set("entity_id", entity_id)
});