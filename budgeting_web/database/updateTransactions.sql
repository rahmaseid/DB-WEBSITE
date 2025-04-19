-- Category 1: Retail (Walmart, Target, Amazon, eBay, Dollar Tree, Etsy)
UPDATE Transactions
SET category_id = 1
WHERE transaction_location IN (
        'Walmart',
        'Target',
        'Amazon',
        'eBay',
        'Dollar Tree',
        'Etsy'
    );
-- Category 2: Grocery (Trader Joe's, Whole Foods Market, Aldi, Costco)
UPDATE Transactions
SET category_id = 2
WHERE transaction_location IN (
        'Trader Joe''s',
        'Whole Foods Market',
        'Aldi',
        'Costco'
    );
-- Category 3: Tech & Electronics (Best Buy, GameStop)
UPDATE Transactions
SET category_id = 3
WHERE transaction_location IN ('Best Buy', 'GameStop');
-- Category 4: Pharmacy (Walgreens, CVS)
UPDATE Transactions
SET category_id = 4
WHERE transaction_location IN ('Walgreens', 'CVS');
-- Category 5: Books & Office (Barnes & Noble, Staples, Office Depot)
UPDATE Transactions
SET category_id = 5
WHERE transaction_location IN ('Barnes & Noble', 'Staples', 'Office Depot');
-- Category 6: Clothing & Accessories (H&M, Zara, Old Navy, Forever 21, Gap, Shein, Foot Locker, Macy's, Nordstrom)
UPDATE Transactions
SET category_id = 6
WHERE transaction_location IN (
        'H&M',
        'Zara',
        'Old Navy',
        'Forever 21',
        'Gap',
        'Shein',
        'Foot Locker',
        'Macy''s',
        'Nordstrom'
    );
-- Category 7: Home & Garden (Home Depot, Lowe's, IKEA, Wayfair, Bed Bath & Beyond)
UPDATE Transactions
SET category_id = 7
WHERE transaction_location IN (
        'Home Depot',
        'Lowe''s',
        'IKEA',
        'Wayfair',
        'Bed Bath & Beyond'
    );
-- Category 8: Beauty & Personal Care (Ulta, Sephora)
UPDATE Transactions
SET category_id = 8
WHERE transaction_location IN ('Ulta', 'Sephora');
-- Category 9: Automotive (AutoZone)
UPDATE Transactions
SET category_id = 9
WHERE transaction_location = 'AutoZone';
-- Category 10: Pet Supplies (PetSmart)
UPDATE Transactions
SET category_id = 10
WHERE transaction_location = 'PetSmart';