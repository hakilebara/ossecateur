import Ember from 'ember';

export default Ember.Controller.extend({
  errorMessage: "",
  token: localStorage.token,
  tokenChanged: function() {
    localStorage.token = this.get('token');
  }.observes('token'),
  reset: function () {
    this.setProperties({
        username: "",
        password: "",
        errorMessage: "",
    });
  },
  actions: {
    login: function () {
      var self = this;
      var data = this.getProperties('username', 'password');
      self.set('errorMessage', null);
       // TODO: Move API authentication url to the config/environment.js
      Ember.$.post('http://localhost/api/ossec/auth', data).then(function(response) {

        // Check the response for the token
        var jsonResponse = Ember.$.parseJSON(response);
        self.set('errorMessage', jsonResponse.message);

        if (jsonResponse.success) {
          // If authentication is successful, store the authentication token in localStorage and redirect to the alerts page
          self.set('token', jsonResponse.token);
          self.transitionToRoute('alerts');
        }
      });
    },
  }
});
