import DS from 'ember-data';

export default DS.RESTAdapter.extend({
  //host: 'http://localhost/api/ossec',
  namespace: 'ossecateur/api',
  headers: function () {
    return { "token": localStorage.token };
  }.property().volatile()
});
