import Ember from 'ember';
import config from './config/environment';

var Router = Ember.Router.extend({
  location: config.locationType
});

Router.map(function() {
  this.resource('alerts', {path: '/'});
  this.resource('acknowledgements');
  this.route('alerts');
  this.route('login');
});

export default Router;
