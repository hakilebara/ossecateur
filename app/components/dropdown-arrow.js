import Ember from 'ember';

export default Ember.Component.extend({
  classNameBindings: ['isToggled:arrow-down:arrow-right'],
  isToggled: function () {
    return this.get('toggled');
  }.property('toggled')
});
