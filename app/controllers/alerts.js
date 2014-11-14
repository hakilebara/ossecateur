import Ember from 'ember';

export default Ember.ArrayController.extend({
  itemController: 'alert',
  needs: ['login'],

  // Extract username from API authentication token
  // TODO: move this computed property to the application controller
  username: function() {
    var token = this.get('controllers.login').token;
    var username = atob(token).split(":")[0];
    return username;
  }.property('controllers.login.token'),
  actions: {
    showMainSearchBar: function() {
        this.set('isMainSearchBarActive', true);
    },
    hideMainSearchBar: function() {
        this.set('isMainSearchBarActive', false);
    }
  }
});
