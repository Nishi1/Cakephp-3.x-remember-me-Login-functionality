	  
  
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
