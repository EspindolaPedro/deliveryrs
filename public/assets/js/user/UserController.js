// import UserService from './UserService.js'

// class User {

//     constructor() {
//       this.loginForm = document.getElementById("login-form");
//       this.init();
//     }
  
//     init() {
//       if (this.loginForm) {
//         this.loginForm.addEventListener("submit", async (event) => {
//           event.preventDefault();
//           await this.handleLogin();
//         });
//       }
//     }
  
//     async handleLogin() {
//       const email = document.getElementsByName("email")[0].value;
//       const password = document.getElementsByName("password")[0].value;
  
//       if (email == "" || password == "") {
//         Toastify({
//           text: "Todos os campos precisam ser preenchidos!",
//           duration: 3000,
//           gravity: "top", 
//           position: "center",
//           backgroundColor: "linear-gradient(to right, #000, #111)" // Personalização da cor
//         }).showToast();
//         return; 
//       }
      
//       try {
//         const userService = new UserService();
//         const res = await userService.handleLogin(email, password);

//         if (res) {
//             window.location.href = '/admin/categoria';
//             return;
//         } else {
//             Toastify({
//                 text:  "Erro ao fazer login",
//                 duration: 3000,
//                 gravity: "top", 
//                 position: "center",
//                 backgroundColor: "linear-gradient(to right, #000, #111)" // Personalização da cor
//               }).showToast();
//         }
//       } catch (e) {       
            
        
//       }
//     }
//   }
  
//   export default User;
  