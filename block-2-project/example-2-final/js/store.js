// Մեկ զամբյուղ և մեկ հավանածների ցուցակ ամբողջ կայքի համար։
// Մոդուլը կատարվում է մեկ անգամ, ինչքան էլ import անենք (JS Դաս 18), ուստի օբյեկտները ընդհանուր են։

import { Cart } from "./models/Cart.js";
import { Wishlist } from "./models/Wishlist.js";
import { KEYS } from "./config.js";

export const cart = new Cart(KEYS.cart);
export const wishlist = new Wishlist(KEYS.wishlist);
