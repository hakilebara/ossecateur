import Ember from 'ember';

export default Ember.ObjectController.extend({
  isAlertDetailsActive: false,
  isAlertSelected: false,

  // this computed property returns true if the alert object has been acknowledged
  // and retuns false otherwise 
  isAlertAcknowledged: Ember.computed.notEmpty('acknowledgement._data'),
  isAckDropDownActive: false,
  newComment:"",
  isCommentEmpty: Ember.computed.empty('newComment'),
  needs: ['alerts'],
  status: function() {
    if (this.get('isAlertAcknowledged')) {
      return "acknowledged";
    } else {
      return "open";
    }
  }.property('isAlertAcknowledged'),
  statuses: [{label: "acknowledged", value: true}, {label: "open", value: false}],
  errorMessage: "",
  actions: {
      toggleAlertDetails: function() {
        //console.log('DEBUG controllers/alerts.js 01: OK');
        this.set('isAlertDetailsActive', !this.get('isAlertDetailsActive'));
      },
      toggleAlertSelection: function() {
        this.set('isAlertSelected', !this.get('isAlertSelected'));
      },
      // action bubbled up from ack-dropdown component
      // TODO: rename into 'acceptAcknowledgementChange' ?
      updateAlertAcknowledgement: function(selectedAckValue) {
        // if the alert ack status has been modified
        console.log('selectedAckValue');
        if(this.get('isAlertAcknowledged') !== selectedAckValue){
         
          if (selectedAckValue === true) {
            // create an acknowlegement for this alert
            this.send('acknowledgeAlert');
          } else {
            // remove existing acknowledment
            //console.log('remote existing ack');
          }
        } else {
          console.log('no change');
        }
      },
      acknowledgeAlert: function() {
        var acknowledgement = this.store.createRecord('acknowledgement', {
          owner: this.get('controllers.alerts.username'),
          timestamp: new Date(),
          alert: this.get('model'),
        });
        acknowledgement.save().then(function() {
          //this.set('acknowledgement', acknowledgement);
          //this.get('model').reload();
        });
      },
      saveComment: function() {
        if (this.get('isCommentEmpty')){
          this.set('errorMessage', "Comment cannot be empty");
          return false;
        }
        this.set('errorMessage', null);
        var comment = this.store.createRecord('comment', {
          author: this.get('controllers.alerts.username'),
          timestamp: new Date(),
          content: this.get('newComment'),
          alert: this.get('model'),
        });
        comment.save().then(function() {
        });
        this.set('newComment', null);
      }
  }
});
