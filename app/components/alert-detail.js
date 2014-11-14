import Ember from 'ember';

export default Ember.Component.extend({
  animate: function() {
      this.$().fadeIn();
      console.log(this.$());
  }.on('didInsertElement')
});
