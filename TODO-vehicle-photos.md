# Add Vehicle Entry/Exit Photo Display to Spots View

**Task**: Replace 'Terisi' status next to spot number with small entry photo + plate number.

**Steps:**
1. [ ] Add `currentParking` relationship to ParkingSpot model (active parking).
2. [ ] Update SpotsController.php to load currentParking for spots.
3. [ ] Edit resources/views/spots.blade.php: For occupied spots, show thumbnail photo + plate instead of 'Terisi'.
4. [ ] Test /spots page.

**Progress:**
1. [✅] Added `currentParking` relationship to ParkingSpot model.

**Progress:**
1. [✅] Added `currentParking` relationship to ParkingSpot model.
2. [✅] Updated SpotsController.php to load currentParking.

**Progress:**
1. [✅] Added `currentParking` relationship to ParkingSpot model.
2. [✅] Updated SpotsController.php to load currentParking.
3. [✅] Edited resources/views/spots.blade.php - occupied spots now show entry photo thumbnail + plate code instead of 'Terisi'.

**Complete!**
Test /spots - occupied spots show photo/plate.

Photos: 20x80px thumbnail with plate overlay.



