public function login(){
	$this->viewBuilder()->setLayout('admin_login');
	if ($this->request->is('post')) {			
		$user = $this->Auth->identify();
		if ($user) {
			$this->Auth->setUser($user);				
			if (isset($this->request->getData()['xx'])) {					
				$user_id = $this->Auth->user('id');										
				$this->Cookie->write('hooty_remembertoken', $this->encryptpass($this->request->data['email'])."^".base64_encode($this->request->data['password']), true);					
				$user = $this->Admins->get($user_id);
				$user->remember_token = $this->encryptpass($this->request->data['email']);
				$this->Admins->save($user);													
				unset($this->request->getData()['xx']);
			}				
			return $this->redirect($this->Auth->redirectUrl());
		} else {
			$this->Flash->error(__('Username or password is incorrect'));
			$this->redirect('/admin');
		}
	} elseif(empty($this->data)) {			
		$hooty_remembertoken = $this->Cookie->read('hooty_remembertoken');			
		if (!is_null($hooty_remembertoken)) {
			$hooty_remembertoken = explode("^",$hooty_remembertoken);							
			$data = $this->Admins->find('all', ['conditions' => ['remember_token'=>$hooty_remembertoken[0]]], ['fields'=>['email','password']])->first();			
			$this->request->data['email'] = $data->email;
			$this->request->data['password'] = base64_decode($hooty_remembertoken[1]);		
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

private function encryptpass($password,$method = 'md5',$crop = true,$start = 4, $end = 10){
	if($crop){
		$password = $method(substr($method($password),$start,$end));
	}else{
		$password = $method($password);
	}
	return $password;
}

public function logout() {
	$this->Auth->logout();
	$this->request->session()->destroy();
	$this->Cookie->delete('username');
	$this->Cookie->delete('password');
	$this->Flash->success(__('You are now logged out'));
	return $this->redirect(['controller' => 'Admins', 'action' => 'login']);
}
