import { db } from "../../lib/firebase";
import { collection, addDoc, getDocs } from "firebase/firestore";

export default async function handler(req, res) {
  if (req.method === "POST") {
    try {
      const order = req.body;
      await addDoc(collection(db, "orders"), order);
      return res.status(201).json({ message: "تم حفظ الطلب", order });
    } catch (e) {
      return res.status(500).json({ message: "خطأ في التخزين", error: e });
    }
  }

  if (req.method === "GET") {
    try {
      const snapshot = await getDocs(collection(db, "orders"));
      const orders = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
      return res.status(200).json({ orders });
    } catch (e) {
      return res.status(500).json({ message: "خطأ في الجلب", error: e });
    }
  }

  res.status(405).json({ message: "Method Not Allowed" });
}