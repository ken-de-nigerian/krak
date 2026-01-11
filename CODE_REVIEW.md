# Code Review Report
## Stone Gatte Holdings Codebase

**Date:** 2024  
**Reviewer:** AI Code Reviewer  
**Framework:** Custom PHP MVC with Medoo ORM

---

## Executive Summary

This code review identifies critical issues, particularly **N+1 query problems**, code structure issues, and recommendations for best practices. The codebase is a financial platform with features for investments, deposits, withdrawals, and user management.

### Priority Issues Found:
1. **Critical:** Multiple N+1 query problems in Cron jobs and models
2. **High:** Very large model files (User.php: 3201 lines, Admin.php: 4520 lines)
3. **High:** Repeated settings queries in controllers
4. **Medium:** Missing query result caching
5. **Medium:** Code duplication across models

---

## 1. N+1 Query Problems

### 1.1 Critical: N+1 Query in Cron Job - Investment Processing

**Location:** `app/models/Cron.php:48-157`

**Problem:**
```php
foreach ($investments as $data) {
    $user = $this->db->get('user', '*', ['userid' => $data['userid']]);  // ❌ N+1 Query
    $data['plan'] = $this->db->get('plans', '*', ['planId' => $data['planId']]);  // ❌ N+1 Query
    // ... more code
}
```

**Impact:** If processing 100 investments, this executes 200+ database queries (100 for users + 100 for plans).

**Solution:**
```php
// ✅ Fetch all users in one query
$userIds = array_column($investments, 'userid');
$users = $this->db->select('user', '*', ['userid' => $userIds]);
$usersByUserId = [];
foreach ($users as $user) {
    $usersByUserId[$user['userid']] = $user;
}

// ✅ Fetch all plans in one query
$planIds = array_column($investments, 'planId');
$plans = $this->db->select('plans', '*', ['planId' => $planIds]);
$plansByPlanId = [];
foreach ($plans as $plan) {
    $plansByPlanId[$plan['planId']] = $plan;
}

// Now use in loop
foreach ($investments as $data) {
    $user = $usersByUserId[$data['userid']] ?? null;
    $data['plan'] = $plansByPlanId[$data['planId']] ?? null;
    // ... rest of code
}
```

---

### 1.2 Critical: N+1 Query in Cron Job - Deposit Processing

**Location:** `app/models/Cron.php:455-534`

**Problem:**
```php
foreach ($deposits as $data) {
    $user = $this->db->get('user', '*', ['userid' => $data['userid']]);  // ❌ N+1 Query
    $data['gateway'] = $this->db->get('gateway_currencies', '*', ['method_code' => $data['method_code']]);  // ❌ N+1 Query
    // ... more code
}
```

**Solution:** Apply the same batch fetching pattern as above.

---

### 1.3 High: Repeated Settings Queries

**Location:** Multiple controllers

**Problem:** Settings are fetched repeatedly in every controller method:
- `app/controllers/user.php`: Settings fetched in almost every method
- `app/controllers/admin.php`: Settings fetched multiple times

**Current Pattern:**
```php
$settingsModel = $this->model('Settings');
$data['settings'] = $settingsModel->get();  // ❌ Query executed every time
```

**Solution:** Cache settings or fetch once per request:
```php
// In Controller base class, cache settings
protected ?array $settingsCache = null;

protected function getSettings(): array {
    if ($this->settingsCache === null) {
        $settingsModel = $this->model('Settings');
        $this->settingsCache = $settingsModel->get();
    }
    return $this->settingsCache;
}
```

---

### 1.4 Medium: Multiple Count Queries in Navigation

**Location:** `app/core/Controller.php:311-329` (getAdminSidenav)

**Problem:**
```php
$data['banned-users-count'] = $adminModel->BannedUsersCount();        // Query 1
$data['kyc-unverified-count'] = $adminModel->KYCUnverifiedCount();    // Query 2
$data['kyc-pending-count'] = $adminModel->KYCPendingCount();          // Query 3
$data['running-investments-count'] = $investmentModel->CountRunningInvestments();  // Query 4
$data['pending-withdrawals-count'] = $adminModel->CountPendingWithdrawals();      // Query 5
$data['initiated-withdrawals-count'] = $adminModel->CountInitiatedWithdrawals();  // Query 6
$data['initiated-deposits-count'] = $adminModel->CountInitiatedDeposits();        // Query 7
$data['pending-deposits-count'] = $adminModel->CountPendingDeposits();            // Query 8
```

**Solution:** Use UNION or single query with conditional counting, or cache counts:
```php
// Option 1: Single query with conditional counting
$counts = $this->db->query("
    SELECT 
        COUNT(CASE WHEN status = 2 THEN 1 END) as banned_users,
        COUNT(CASE WHEN kyc_status = 0 THEN 1 END) as kyc_unverified,
        -- etc.
    FROM user
")->fetchAll();

// Option 2: Cache counts (better for frequently accessed data)
// Implement Redis/Memcached caching with TTL
```

---

## 2. Code Structure & Standards

### 2.1 Critical: Oversized Model Files

**Problem:**
- `app/models/User.php`: **3,201 lines** (should be < 500 lines)
- `app/models/Admin.php`: **4,520 lines** (should be < 500 lines)

**Impact:**
- Hard to maintain
- Difficult to test
- Violates Single Responsibility Principle
- Performance issues (more memory usage)

**Solution:** Split into smaller, focused classes:

```php
// Split User model into:
app/models/User/User.php          // Core user operations
app/models/User/UserProfile.php   // Profile management
app/models/User/UserWallet.php    // Wallet operations
app/models/User/UserTransaction.php // Transaction operations
app/models/User/UserInvestment.php  // Investment operations
app/models/User/UserDeposit.php     // Deposit operations
app/models/User/UserWithdrawal.php  // Withdrawal operations
```

---

### 2.2 High: Code Duplication

**Problem:** Similar methods repeated across models with slight variations:
- `getDeposits*`, `getWithdrawals*`, `getInvestments*` methods follow same pattern
- Status filtering duplicated (pending, completed, initiated, cancelled)

**Solution:** Create base repository classes:
```php
// app/models/BaseRepository.php
abstract class BaseRepository extends Model {
    protected function getByStatus(string $table, string $userid, int $status, int $limit = 5): array {
        return $this->db->select($table, '*', [
            'userid' => $userid,
            'status' => $status,
            'ORDER' => ['created_at' => 'DESC'],
            'LIMIT' => $limit
        ]);
    }
    
    protected function getByStatusPaginated(string $table, string $userid, int $status, int $page, int $limit = 5): array {
        $offset = ($page - 1) * $limit;
        return $this->db->select($table, '*', [
            'userid' => $userid,
            'status' => $status,
            'ORDER' => ['created_at' => 'DESC'],
            'LIMIT' => [$offset, $limit]
        ]);
    }
}
```

---

### 2.3 Medium: Inefficient Settings Retrieval

**Location:** `app/models/Settings.php:98-130`

**Problem:**
```php
public function getCurrency() {
    $settings = $this->db->select('settings', '*', ["id" => 1]);
    $row = null;
    foreach ($settings as $row) {}  // ❌ Unnecessary loop
    
    $q1 = $this->db->select('currency', '*', ["id" => $row["currency"]]);
    foreach ($q1 as $r1) {}  // ❌ Unnecessary loop
    
    return $r1["currency_symbol"];
}
```

**Solution:**
```php
public function getCurrency(): string {
    $settings = $this->db->get('settings', '*', ["id" => 1]);
    if (!$settings) {
        return '';
    }
    
    $currency = $this->db->get('currency', '*', ["id" => $settings["currency"]]);
    return $currency ? $currency["currency_symbol"] : '';
}
```

---

### 2.4 Medium: Model Instantiation Pattern

**Location:** `app/core/Controller.php:68-76`

**Problem:** Models are loaded with `require_once` on each call, which is inefficient.

**Current:**
```php
public function model(string $model): object {
    require_once(__DIR__ . '/../models/' . $model . '.php');
    $class = 'Fir\Models\\' . $model;
    return new $class($this->db);
}
```

**Solution:** Use autoloading (Composer PSR-4):
```json
// composer.json
{
    "autoload": {
        "psr-4": {
            "Fir\\": "app/"
        }
    }
}
```

Then remove `require_once`:
```php
public function model(string $model): object {
    $class = 'Fir\Models\\' . $model;
    if (!class_exists($class)) {
        throw new \Exception("Model {$model} not found");
    }
    return new $class($this->db);
}
```

---

## 3. Best Practices & Standards

### 3.1 Missing Query Result Caching

**Problem:** Frequently accessed, rarely changing data is queried repeatedly:
- Settings
- Plans
- Gateway methods
- User counts in navigation

**Solution:** Implement caching layer:
```php
// Simple in-memory cache (for single-server)
class Cache {
    private static array $cache = [];
    
    public static function get(string $key): mixed {
        return self::$cache[$key] ?? null;
    }
    
    public static function set(string $key, mixed $value, int $ttl = 3600): void {
        self::$cache[$key] = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
    }
    
    public static function remember(string $key, callable $callback, int $ttl = 3600): mixed {
        $cached = self::get($key);
        if ($cached !== null && $cached['expires'] > time()) {
            return $cached['value'];
        }
        $value = $callback();
        self::set($key, $value, $ttl);
        return $value;
    }
}

// Usage
$settings = Cache::remember('settings', function() use ($settingsModel) {
    return $settingsModel->get();
}, 3600);
```

**Better Solution:** Use Redis or Memcached for multi-server deployments.

---

### 3.2 Missing Database Indexes

**Recommendation:** Ensure indexes exist on frequently queried columns:
```sql
-- Recommended indexes
CREATE INDEX idx_userid_status ON deposits(userid, status);
CREATE INDEX idx_userid_status ON withdrawals(userid, status);
CREATE INDEX idx_userid_status ON invests(userid, status);
CREATE INDEX idx_userid ON transactions(userid);
CREATE INDEX idx_status ON invests(status);
CREATE INDEX idx_created_at ON transactions(created_at);
```

---

### 3.3 Transaction Handling

**Problem:** No explicit transaction handling for multi-step operations.

**Example:** `app/models/Admin.php:178-199` (approveDeposit)
- Updates user balance
- Updates deposit status
- If second query fails, data is inconsistent

**Solution:** Use database transactions:
```php
public function approveDeposit(int $depositId, string $userid, float $amount): int {
    try {
        $this->db->pdo->beginTransaction();
        
        $user = $this->db->get('user', '*', ["userid" => $userid]);
        $newBalance = $user['interest_wallet'] + $amount;
        
        $this->db->update('user', ['interest_wallet' => $newBalance], ['userid' => $userid]);
        $update = $this->db->update('deposits', ['status' => 1], ['depositId' => $depositId]);
        
        $this->db->pdo->commit();
        return $update->rowCount();
    } catch (Exception $e) {
        $this->db->pdo->rollBack();
        error_log('Error in approveDeposit(): ' . $e->getMessage());
        return 0;
    }
}
```

---

### 3.4 Error Handling

**Problem:** Many methods catch exceptions but only log them, returning empty arrays/zero.

**Current:**
```php
try {
    return $this->db->select(...);
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    return [];
}
```

**Better:** Use custom exceptions and proper error handling:
```php
class ModelException extends Exception {}

try {
    return $this->db->select(...);
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    throw new ModelException('Failed to retrieve data', 0, $e);
}
```

---

### 3.5 Type Safety

**Problem:** Mixed return types and inconsistent type hints.

**Example:** `app/models/Settings.php:98` - `getCurrency()` has no return type.

**Solution:** Add proper type hints:
```php
public function getCurrency(): string {
    // ...
}
```

---

## 4. Performance Recommendations

### 4.1 Database Query Optimization

1. **Use SELECT only needed columns** instead of `SELECT *`
2. **Add LIMIT clauses** to all list queries
3. **Use pagination** consistently
4. **Consider read replicas** for reporting queries

### 4.2 Query Batching

Create helper methods for batch fetching:
```php
// app/models/BaseModel.php
protected function batchGet(string $table, array $ids, string $idColumn = 'id'): array {
    if (empty($ids)) {
        return [];
    }
    
    $results = $this->db->select($table, '*', [$idColumn => $ids]);
    $indexed = [];
    foreach ($results as $row) {
        $indexed[$row[$idColumn]] = $row;
    }
    return $indexed;
}
```

---

## 5. Security Considerations

### 5.1 SQL Injection

**Status:** ✅ Good - Using Medoo ORM prevents SQL injection when using parameterized queries.

**Note:** Ensure all user input is passed through Medoo's query builder, never concatenated into raw SQL.

### 5.2 Input Validation

**Recommendation:** Add validation at model level, not just controller level.

---

## 6. Summary of Recommended Actions

### Priority 1 (Critical - Fix Immediately)
1. ✅ Fix N+1 queries in `app/models/Cron.php` (investment and deposit processing)
2. ✅ Implement settings caching
3. ✅ Add database transactions for multi-step operations

### Priority 2 (High - Fix Soon)
1. ✅ Split large model files (User.php, Admin.php)
2. ✅ Create base repository classes to reduce duplication
3. ✅ Optimize navigation queries (combine count queries)

### Priority 3 (Medium - Fix When Possible)
1. ✅ Implement query result caching (Redis/Memcached)
2. ✅ Add database indexes
3. ✅ Fix inefficient Settings methods
4. ✅ Improve error handling with custom exceptions
5. ✅ Add proper type hints throughout

---

## 7. Code Examples: Before & After

### Example 1: Fixing N+1 Query in Cron

**Before:**
```php
foreach ($investments as $data) {
    $user = $this->db->get('user', '*', ['userid' => $data['userid']]);
    $data['plan'] = $this->db->get('plans', '*', ['planId' => $data['planId']]);
    // Process...
}
```

**After:**
```php
// Batch fetch all users
$userIds = array_unique(array_column($investments, 'userid'));
$users = $this->db->select('user', '*', ['userid' => $userIds]);
$usersMap = array_column($users, null, 'userid');

// Batch fetch all plans
$planIds = array_unique(array_column($investments, 'planId'));
$plans = $this->db->select('plans', '*', ['planId' => $planIds]);
$plansMap = array_column($plans, null, 'planId');

foreach ($investments as $data) {
    $user = $usersMap[$data['userid']] ?? null;
    $data['plan'] = $plansMap[$data['planId']] ?? null;
    // Process...
}
```

---

## 8. Testing Recommendations

1. **Add unit tests** for model methods
2. **Add integration tests** for database operations
3. **Performance tests** to measure query improvements
4. **Load testing** to verify N+1 query fixes

---

## Conclusion

The codebase has a solid foundation but suffers from common performance issues, especially N+1 queries and oversized files. Addressing these issues will significantly improve performance, maintainability, and scalability.

**Estimated Impact:**
- N+1 query fixes: **60-80% reduction** in database queries for Cron jobs
- Settings caching: **50-70% reduction** in redundant queries
- Code refactoring: **Improved maintainability** and testability

---

**Next Steps:**
1. Review and prioritize recommendations
2. Create tickets for each priority item
3. Implement fixes incrementally
4. Monitor performance improvements
5. Document changes

