# Codebase Refactoring Documentation

This document describes the refactoring performed to modernize the PHP codebase following SOLID principles, particularly SRP (Single Responsibility Principle) and OCP (Open/Closed Principle).

## Overview

The codebase has been refactored to include:
- **Proper Routing System**: Route definitions with middleware support
- **Service Container**: Dependency injection container
- **Repository Pattern**: Abstracted data access layer
- **ORM Layer**: Model relationships with eager loading to prevent N+1 queries
- **Caching System**: File-based caching with interfaces
- **Middleware System**: Proper middleware pipeline with interfaces
- **Service Layer**: Business logic separation

## Architecture Changes

### 1. Routing System

**Location**: `app/core/Router.php`, `app/core/Route.php`, `app/routes/web.php`

The new routing system provides:
- Route definitions in `app/routes/web.php`
- Route groups with prefix and middleware
- HTTP method support (GET, POST, PUT, DELETE)
- Route parameters
- Middleware assignment per route

**Example**:
```php
$router->group(['prefix' => 'user', 'middleware' => ['Auth']], function ($router) {
    $router->get('/dashboard', 'User@dashboard');
    $router->get('/profile', 'User@profile');
});
```

### 2. Service Container

**Location**: `app/core/Container.php`

Implements dependency injection following SRP:
- Automatic dependency resolution
- Singleton support
- Interface binding

**Usage**:
```php
$container = new Container();
$container->singleton(Medoo::class, function() {
    return $db;
});
$db = $container->get(Medoo::class);
```

### 3. Repository Pattern

**Location**: `app/core/Repository/`, `app/repositories/`

Separates data access logic from business logic (SRP):
- `RepositoryInterface`: Contract for repositories
- `BaseRepository`: Base implementation
- Specific repositories: `UserRepository`, etc.

**Benefits**:
- Testability
- Easy to swap data sources
- Single responsibility per repository

### 4. ORM Layer with N+1 Prevention

**Location**: `app/core/ORM/`

**Features**:
- Base `Model` class with CRUD operations
- Relationship definitions (`belongsTo`, `hasMany`)
- Eager loading to prevent N+1 queries
- Caching integration

**Example - Preventing N+1**:
```php
// Before (N+1 problem):
$users = $userModel->findAll();
foreach ($users as $user) {
    $investments = $investmentModel->findAll(['userid' => $user['userid']]); // N queries!
}

// After (1 query):
$users = $userModel->findAll();
$users = $userModel->eagerLoad($users, ['investments']); // 1 query for all relationships
```

### 5. Caching System

**Location**: `app/core/Cache/`

**Components**:
- `CacheInterface`: Contract for cache implementations (OCP)
- `FileCache`: File-based cache implementation

**Features**:
- TTL support
- Multiple operations (get, set, delete, clear)
- Batch operations

**Usage**:
```php
$cache = new FileCache();
$cache->set('key', 'value', 3600); // Cache for 1 hour
$value = $cache->get('key');
```

### 6. Middleware System

**Location**: `app/core/Middleware/`, `app/middleware/`

**Components**:
- `MiddlewareInterface`: Contract for middleware (OCP)
- `MiddlewarePipeline`: Processes middleware chain
- Implementations: `CsrfToken`, `Auth`, `AdminAuth`

**Benefits**:
- Open for extension (new middleware)
- Closed for modification (existing code)
- Reusable across routes

### 7. Service Layer

**Location**: `app/services/`

Separates business logic from controllers (SRP):
- Controllers handle HTTP concerns
- Services handle business logic
- Repositories handle data access

**Example**:
```php
class UserService {
    public function getUserWithRelations($userId) {
        // Business logic here
        // Uses repository for data access
        // Handles caching
    }
}
```

## SOLID Principles Applied

### Single Responsibility Principle (SRP)

- **Controllers**: Handle HTTP requests/responses only
- **Services**: Handle business logic only
- **Repositories**: Handle data access only
- **Models**: Handle data structure and relationships only
- **Middleware**: Handle specific cross-cutting concerns

### Open/Closed Principle (OCP)

- **Interfaces**: `CacheInterface`, `MiddlewareInterface`, `RepositoryInterface`
- **Extensibility**: New cache drivers, middleware, repositories can be added without modifying existing code
- **Polymorphism**: Different implementations can be swapped

## N+1 Query Prevention

The ORM layer includes eager loading to prevent N+1 queries:

1. **Relationship Definition**: Models define relationships
2. **Eager Loading**: `eagerLoad()` method loads all related data in batch
3. **Automatic Batching**: Related records are fetched in single queries

**Example**:
```php
// Load users with their investments (prevents N+1)
$users = $userModel->findAll();
$users = $userModel->eagerLoad($users, ['investments', 'deposits']);
```

## Migration Guide

### For Controllers

Controllers can now use services instead of direct model access:

```php
// Before
$userModel = $this->model('User');
$user = $userModel->getUser($id);

// After
$userService = $container->get(UserService::class);
$user = $userService->getUserById($id);
```

### For Models

Models should extend the new ORM Model class:

```php
class UserModel extends \Fir\Core\ORM\Model {
    protected $table = 'user';
    protected $primaryKey = 'userid';
    
    public function investments() {
        return $this->hasMany(InvestmentModel::class);
    }
}
```

### For Routes

Define routes in `app/routes/web.php`:

```php
$router->get('/users', 'User@index');
$router->post('/users', 'User@store')->middleware('CsrfToken');
```

## Backward Compatibility

The refactored `App.php` maintains backward compatibility:
- Legacy routing still works
- Existing controllers continue to function
- Gradual migration is possible

## Testing

The new architecture is more testable:
- Services can be mocked
- Repositories can be swapped
- Dependencies are injected
- Interfaces enable easy mocking

## Performance Improvements

1. **Caching**: Reduces database queries
2. **Eager Loading**: Prevents N+1 queries
3. **Service Container**: Reuses instances (singletons)
4. **Repository Pattern**: Optimizes data access

## Next Steps

1. Gradually migrate existing controllers to use services
2. Convert existing models to use new ORM
3. Add more middleware as needed
4. Implement additional cache drivers (Redis, Memcached)
5. Add query builder for complex queries
6. Implement database migrations

## File Structure

```
app/
├── core/
│   ├── Cache/
│   │   ├── CacheInterface.php
│   │   └── FileCache.php
│   ├── Middleware/
│   │   ├── MiddlewareInterface.php
│   │   └── MiddlewarePipeline.php
│   ├── ORM/
│   │   ├── Model.php
│   │   ├── Relationship.php
│   │   └── [Model implementations]
│   ├── Repository/
│   │   ├── RepositoryInterface.php
│   │   └── BaseRepository.php
│   ├── Container.php
│   ├── Router.php
│   ├── Route.php
│   └── App.php
├── repositories/
│   └── UserRepository.php
├── services/
│   └── UserService.php
├── middleware/
│   ├── CsrfToken.php
│   ├── Auth.php
│   └── AdminAuth.php
└── routes/
    └── web.php
```

