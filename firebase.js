import { initializeApp } from "firebase/app";
import { getFirestore } from "firebase/firestore";

const firebaseConfig = {
  apiKey: "AIza...xyz",
  authDomain: "drinks-orders.firebaseapp.com",
  projectId: "drinks-orders",
  storageBucket: "drinks-orders.appspot.com",
  messagingSenderId: "1234567890",
  appId: "1:1234567890:web:abcdefg"
};

const app = initializeApp(firebaseConfig);
export const db = getFirestore(app);