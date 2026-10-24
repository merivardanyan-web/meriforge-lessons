# Սխեմա · Սևան Բարբեր

```
users                       services                    time_slots
┌────────────────────┐      ┌────────────────────┐      ┌──────────────────────────┐
│ id                 │      │ id                 │      │ id                       │
│ name, email ⓤ      │      │ name, slug ⓤ       │      │ staff_id FK → users      │──┐
│ phone              │      │ description        │      │ starts_at                │  │
│ role (client)      │      │ duration_min ≤ 60  │      │ ⓤ (staff_id, starts_at)  │  │
│ password           │      │ price              │      └──────────────────────────┘  │
└────────────────────┘      │ is_active          │                   ▲                │
     ▲         ▲            │ deleted_at (soft)  │                   │                │
     │         └────────────┴────────────────────┴──── staff ────────┼────────────────┘
     │                               ▲                               │
     │      bookings                 │                               │
     │      ┌────────────────────────┴───────────┐                   │
     └──────│ user_id FK → users       restrict  │                   │
            │ service_id FK → services restrict  │                   │
            │ time_slot_id FK → time_slots restr.│───────────────────┘
            │ active_slot_id ⓤ nullable  ⭐      │  = time_slot_id, երբ booked/done
            │ price      ← գինը ամրագրման պահին  │  = NULL, երբ cancelled
            │ status (booked · done · cancelled) │
            │ note (255), cancelled_at           │
            └────────────────────────────────────┘
ⓤ = UNIQUE
```

## Ինչու այսպես

| Որոշում | Ինչու |
|---|---|
| `time_slots`-ը առանձին աղյուսակ, ոչ «վարսավիրի գրաֆիկ» | Ազատ ժամը մեկ հարցում է․ `time_slots` առանց ակտիվ ամրագրման։ Վարսավիրը կարող է հանել մեկ ժամ (բժշկի մոտ է) |
| `UNIQUE(staff_id, starts_at)` | Նույն վարսավիրը նույն ժամին երկու պատուհան չի կարող ունենալ․ «ավելացնել 10–19» կրկին սեղմելը ոչինչ չի կրկնում (`insertOrIgnore`) |
| `active_slot_id UNIQUE nullable` ⭐ | Կրկնակի ամրագրումը արգելում է բազան։ `NULL`-ները UNIQUE-ում չեն բախվում, ուստի չեղարկվածները չեն խանգարում |
| `bookings.price` | Ծառայության գինը փոխվի՝ հին ամրագրումները չեն փոխվի (PHP Դաս 9-ի `order_items.price`) |
| `restrict` բոլոր երեք FK-ում | Ամրագրումը պատմություն է։ Չես կարող ջնջել հաճախորդին, ծառայությանը կամ ժամը, որ ունեն ամրագրումներ |
| `services` soft delete | Ծառայությունը անհետանում է կայքից, բայց հին ամրագրումները ցույց են տալիս անունը (`withTrashed()`) |
| `time_slots.staff_id` cascade | Եթե վարսավիրը ջնջվի և ամրագրումներ չունենա, իր դատարկ ժամերը պետք չեն։ Ամրագրումներով՝ restrict-ը կկանգնեցնի |
| `role` users-ում, ոչ առանձին աղյուսակ | Երեք ֆիքսված դեր, ամեն օգտատեր՝ մեկը։ Առանձին աղյուսակը կլիներ բարդություն առանց օգուտի |

## Ինդեքսներ, որոնք պետք են հարցումներին

- `time_slots.starts_at` — «ազատ ժամերը այս օրը»
- `bookings (user_id, status)` — «իմ ակտիվ ամրագրումները», 3-ի սահմանը
- `bookings.active_slot_id` — UNIQUE-ը նաև ինդեքս է՝ `whereDoesntHave('activeBooking')`
