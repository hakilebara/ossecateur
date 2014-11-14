import Ember from 'ember';

export default Ember.Component.extend({
  tagName: 'tbody',
  classNameBindings: ['isAlertSelected:alert-selected'],
  isDetailsActive: false,
  action: 'updateAlertAcknowledgement',
  actions: {
    toggleDetails: function () {
        this.toggleProperty('isDetailsActive');
        //this.sendAction();
    },
   updateAlertAcknowledgement: function(value) {
     // DEBUG:
     //consolo.log("I am the alert-row controller - you clicked the update ack dropdown");
     this.sendAction('action', value);
   }
  }

});
