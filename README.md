# Cakephp-3.x-remember-me-Login-functionality

If you select the tick in the ‘remember me’ box when you login in any web page with your Username and password, your login credentials will be saved in a cookie on computer. This means that whenever the next time you log on to the same website , you will not need to re-enter the login details again. But if we delete the cookie from our browser or browser do not accept cookies this 'Remember Me' function will not work. You can use the Remember me function in cakephp by using some codes. As its a important part in login function. Check the sample code below:


	public function login(){
		$this->viewBuilder()->setLayout('admin_login');
		if ($this->request->is('post')) {			
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);				
				if (isset($this->request->getData()['xx'])) {
					$username= $this->request->data['email'];
					$password = $this->request->data['password'];					
					$this->Cookie->write('username', $username, true);
					$this->Cookie->write('password', $password, true);					
				    unset($this->request->getData()['xx']);
				}				
				return $this->redirect($this->Auth->redirectUrl());
			}
			else {
			    $this->Flash->error(__('Username or password is incorrect'));
				$this->redirect('/admin');
			}
		} elseif(empty($this->data)) {			
			$username = $this->Cookie->read('username');
			$password=$this->Cookie->read('password');
			if ((!empty($password))&&(!empty($username))) {
				$this->request->data['email']=$username;
				$this->request->data['password']=$password;
				$user = $this->Auth->identify();
				if ($user) {
					$this->Auth->setUser($user);
					$this->redirect($this->Auth->redirectUrl());					
				} else {												
					$this->redirect('/admin');
				}
			}
		}
	}
	
	
    public function logout() {
        $this->Auth->logout();
        $this->request->session()->destroy();
        $this->Cookie->delete('username');
        $this->Cookie->delete('password');
        $this->Flash->success(__('You are now logged out'));
        return $this->redirect(['controller' => 'Admins', 'action' => 'login']);
    }
