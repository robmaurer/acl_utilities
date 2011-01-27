## AclUtilities ##

AclUtilities is a Cakephp plugin.

It permits to only display the link for which the user has access.

For now, AclUtilities contains just one Helper but it has been made into a plugin to make it's use simpler.

Installation:

1. in the views, replace $this->Html->link() by $this->Acl->link()

example:

<?php echo $this->Html->link(__('List Posts', true), array('action' => 'index')); ?>

<?php echo $this->Html->link(__('Edit User', true), array('controller'=>'User','action' => 'edit')); ?>

replaced by

<?php echo $this->Acl->link(__('List Posts', true), array('action' => 'index')); ?>

<?php echo $this->Acl->link(__('Edit User', true), array('controller'=>'User','action' => 'edit')); ?>

Be sure to use an array format for the URL.

2. in the AppController, add the helper AclUtilities.Acl

var $helpers = array([...], 'AclUtilities.Acl');

3. If you are using Auth->allowedActions or Auth->allow()
   Then you have to move them all into AppController::beforeFilter() like the following:

  function beforeFilter() {
    [...]
    // $allowedActions = array([Controller]=>array([action1],[action2]);
    Configure::write('AclUtilities.allowedActions', $allowedActions = array(
      'News' => array('*'), // access to all the actions
      'Groups' => array(), // no access to groups
      'Pages' => array('display'),
      'Posts' => array('index', 'view'),
      'Users' => array('login','register'),
    ));
    
    // now, we need to allow the action for the current module
    if (isset($allowedActions[$this->name]))
      $this->Auth->allow($allowedActions[$this->name]);
  }
  
And this is it; your links are now only displayed when they can be accessed!



** More examples **


1. use of the option 'wrapper': 

<ul>
  <li>
    <?php echo $this->Html->link(__('List Posts', true), array('action' => 'index')); ?>
  <li>
<ul>

<ul>
  <?php echo $this->Acl->link(__('List Posts', true)
                              ,array('action' => 'index')
                              ,array('wrapper'=>'li'); ?>
<ul>

2. another use of the wrapper

<div class="myClass">
  <?php echo $this->Html->link(__('List Posts', true), array('action' => 'index')); ?>
</div>

<?php echo $this->Acl->link(__('List Posts', true)
                           ,array('action' => 'index')
                           ,array('wrapper'=>'<div class="myClass">%s</div>'); ?>

3. use of $this->Acl->check()

<?php if ($this->Acl->check(array('action' => 'index'))): ?>
  <div class="myClass">
    <?php echo $this->Html->link(__('List Posts', true), array('action' => 'index')); ?>
  </div>
<?php endif; ?>