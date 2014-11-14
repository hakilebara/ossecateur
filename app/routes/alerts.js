import Ember from 'ember';

export default Ember.Route.extend({
  model: function() {
      return this.store.find('alert');
  },
  actions: {
    error: function(error, transition) {
      if (error.status === 401) {
        this.transitionTo('login');
      } else {
        //alert('something went wrong');
      }
    }
  }
});
