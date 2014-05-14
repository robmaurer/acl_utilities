<?php
App::uses('Controller', 'Controller');
App::uses('Helper', 'View');
App::uses('AppHelper', 'View/Helper');
App::uses('AclHelper', 'AclUtilities.View/Helper');
App::uses('SessionComponent', 'Controller/Component');

class AclHelperTest extends CakeTestCase {

	public $fixtures = array(
        'aco', 'core.aro_two', 'aros_aco'
    );

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
        $this->Controller = new Controller();
        $this->View = new View($this->Controller);
        $this->AclHelper = new AclHelper($this->View);
        $this->ComponentCollection = new ComponentCollection();
        $this->Session = new SessionComponent($this->ComponentCollection);
    }

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->AclHelper);
        CakeSession::destroy();
	}

/**
 * testHelperInstance
 * this model is the real deal (AclHelper instance of class AclHelper)
 *
 * @return void
 */
	function testHelperInstance() {
		$this->assertTrue(is_a($this->AclHelper, 'AclHelper'));
	}

/**
 * testCheckReturnsFalseForSingleWordAction method
 *
 * @return void
 */
    public function testCheckReturnsFalseForSingleWordAction() {
        $url = array('controller' => 'tpsReports', 'action' => 'view', 5);
        $this->Session->write('Auth.User.id', 3);
        $this->AclHelper->beforeRender($this->View);
        $this->assertFalse($this->AclHelper->check($url), 'Check returned true');
    }

/**
 * testCheckReturnsTrueForSingleWordAction method
 *
 * @return void
 */
    public function testCheckReturnsTrueForSingleWordAction() {
        $url = array('controller' => 'tpsReports', 'action' => 'delete', 5);
        $this->Session->write('Auth.User.id', 1);
        $this->AclHelper->beforeRender($this->View);
        $this->assertTrue($this->AclHelper->check($url), 'Check returned false');
    }

/**
 * testCheckReturnsFalseForMultiWordAction method
 *
 * @return void
 */
    public function testCheckReturnsFalseForMultiWordAction() {
        $url = array('controller' => 'print', 'action' => 'letterSize', 5);
        $this->Session->write('Auth.User.id', 3);
        $this->AclHelper->beforeRender($this->View);
        $this->assertFalse($this->AclHelper->check($url), 'Check returned true');
    }

/**
 * testCheckReturnsTrueForMultiWordAction method
 *
 * @return void
 */
    public function testCheckReturnsTrueForMultiWordAction() {
        $url = array('controller' => 'print', 'action' => 'letterSize', 5);
        $this->Session->write('Auth.User.id', 1);
        $this->AclHelper->beforeRender($this->View);
        $this->assertTrue($this->AclHelper->check($url), 'Check returned false');
    }

/**
 * testLinkDenyWithMultiWordAction method
 *
 * @return void
 */
    public function testLinkDenyWithMultiWordAction() {
        $url = array('controller' => 'print', 'action' => 'letterSize', 5);
        $this->Session->write('Auth.User.id', 3);
        $this->AclHelper->beforeRender($this->View);
		$this->assertEquals('', $this->AclHelper->link('Title', $url));
	}

/**
 * testLinkAllowWithMultiWordAction method
 *
 * @return void
 */
    public function testLinkAllowWithMultiWordAction() {
        $url = array('controller' => 'print', 'action' => 'letterSize', 5);
        $this->Session->write('Auth.User.id', 1);
        $this->AclHelper->beforeRender($this->View);
		$expected = '<a href="/print/letterSize/5">Title</a>';
		$result = $this->AclHelper->link('Title', $url);
		$this->assertEquals($expected, $result);
    }

/**
 * testLinkWrapperExplicit method
 *
 * @return void
 */
    public function testLinkWrapperExplicit() {
        $url = array('controller' => 'tpsReports', 'action' => 'view', 5);
        $this->Session->write('Auth.User.id', 1);
        $this->AclHelper->beforeRender($this->View);
		$expected = '<li><a href="/tpsReports/view/5">Title</a></li>';
		$result = $this->AclHelper->link('Title', $url, array('wrapper' => '<li>%s</li>'));
		$this->assertEquals($expected, $result);
	}

/**
 * testLinkWrapperImplicit method
 *
 * @return void
 */
    public function testLinkWrapperImplicit() {
        $url = array('controller' => 'tpsReports', 'action' => 'view', 5);
        $this->Session->write('Auth.User.id', 1);
        $this->AclHelper->beforeRender($this->View);
		$expected = '<li><a href="/tpsReports/view/5">Title</a></li>';
		$result = $this->AclHelper->link('Title', $url, array('wrapper' => 'li'));
		debug($result);
		$this->assertEquals($expected, $result);
    }

}
