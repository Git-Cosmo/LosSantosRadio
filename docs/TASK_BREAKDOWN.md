# Los Santos Radio - Task Breakdown
**Based on Repository Audit - December 8, 2025**

This document provides actionable tasks to address gaps identified in the repository audit.

---

## Priority Legend
- 🔴 **Critical:** Blocking production deployment
- 🟠 **High:** Important for production quality
- 🟡 **Medium:** Improves quality and maintainability
- 🟢 **Low:** Nice-to-have enhancements

---

## Task Status
- [ ] Not Started
- [x] Completed
- 🚧 In Progress

---

## Phase 1: Bug Fixes (COMPLETED ✅)

### Task 1.1: Fix Theme Variable Scope Bug
**Priority:** 🔴 Critical  
**Status:** [x] Completed  
**Effort:** 5 minutes  
**Impact:** Fixes test failure

**Issue:** Undefined variable `$allowedThemes` in `resources/views/layouts/app.blade.php:3062`

**Solution:** Moved `$allowedThemes` definition outside try-catch block

**Files Changed:**
- `resources/views/layouts/app.blade.php`

**Verification:** All tests now pass ✅

---

## Phase 2: Testing Improvements (Quick Wins)

### Task 2.1: Add WebSocket Integration Tests
**Priority:** 🟡 Medium  
**Status:** [ ] Not Started  
**Effort:** 30-45 minutes  
**Impact:** Ensures real-time updates work correctly

**Subtasks:**
- [ ] Create `tests/Feature/ReverbIntegrationTest.php`
- [ ] Test `NowPlayingUpdated` event is dispatched
- [ ] Test event broadcasts to correct channel
- [ ] Test event payload contains expected data
- [ ] Mock song change in AzuraCastService

**Acceptance Criteria:**
```php
// Test: Event is dispatched when song changes
public function test_now_playing_updated_event_dispatched_on_song_change()
{
    Event::fake();
    
    // Simulate song change
    // ...
    
    Event::assertDispatched(NowPlayingUpdated::class, function ($event) {
        return $event->stationId === 1 
            && $event->nowPlaying instanceof NowPlayingDTO;
    });
}
```

---

### Task 2.2: Add Lyrics Guest Limit Tests
**Priority:** 🟡 Medium  
**Status:** [ ] Not Started  
**Effort:** 30-45 minutes  
**Impact:** Validates monetization flow works

**Subtasks:**
- [ ] Create `tests/Feature/LyricsGuestLimitTest.php`
- [ ] Test guest can view first 4 songs
- [ ] Test guest blocked after 4 songs
- [ ] Test time-based unlock works
- [ ] Test registered users have unlimited access
- [ ] Test session tracking

**Acceptance Criteria:**
```php
// Test: Guest is limited to 4 songs
public function test_guest_limited_to_four_songs()
{
    // View 4 songs
    for ($i = 1; $i <= 4; $i++) {
        $response = $this->get("/lyrics/{$i}");
        $response->assertStatus(200);
    }
    
    // 5th song should be blocked
    $response = $this->get("/lyrics/5");
    $response->assertStatus(403); // or redirect to upsell
}
```

---

### Task 2.3: Add Permission Boundary Tests
**Priority:** 🟡 Medium  
**Status:** [ ] Not Started  
**Effort:** 30-45 minutes  
**Impact:** Ensures admin panel security

**Subtasks:**
- [ ] Create `tests/Feature/PermissionBoundaryTest.php`
- [ ] Test admin can access admin panel
- [ ] Test staff can access admin panel
- [ ] Test regular user cannot access admin panel
- [ ] Test guest redirected to login
- [ ] Test all admin routes protected

**Acceptance Criteria:**
```php
// Test: Regular user cannot access admin panel
public function test_regular_user_cannot_access_admin_panel()
{
    $user = User::factory()->create(); // No roles
    
    $response = $this->actingAs($user)->get('/admin');
    $response->assertStatus(403);
}

// Test: Admin can access admin panel
public function test_admin_can_access_admin_panel()
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    
    $response = $this->actingAs($admin)->get('/admin');
    $response->assertStatus(200);
}
```

---

## Phase 3: Documentation Improvements

### Task 3.1: Create Reverb Production Deployment Guide
**Priority:** 🟡 Medium  
**Status:** [ ] Not Started  
**Effort:** 1-2 hours  
**Impact:** Helps with production deployment

**Subtasks:**
- [ ] Create `docs/REVERB_DEPLOYMENT.md`
- [ ] Document server requirements
- [ ] Document Reverb SSL configuration
- [ ] Document reverse proxy setup (Nginx/Apache)
- [ ] Document supervisord configuration
- [ ] Document testing WebSocket connection
- [ ] Add troubleshooting section

**Sections to Include:**
```markdown
# Reverb Production Deployment Guide

## 1. Server Requirements
- PHP 8.2+
- Redis (recommended)
- SSL certificate

## 2. Environment Configuration
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=...
REVERB_APP_KEY=...
REVERB_HOST=your-domain.com
REVERB_PORT=8080
REVERB_SCHEME=https

## 3. Reverse Proxy Configuration
[Nginx/Apache examples]

## 4. Supervisord Configuration
[Supervisor config to keep Reverb running]

## 5. Testing Connection
[How to verify WebSocket is working]

## 6. Troubleshooting
[Common issues and solutions]
```

---

### Task 3.2: Enhance README with Production Notes
**Priority:** 🟢 Low  
**Status:** [ ] Not Started  
**Effort:** 30 minutes  
**Impact:** Better onboarding for new developers

**Subtasks:**
- [ ] Add "Production Deployment" section to README
- [ ] Link to Reverb deployment guide
- [ ] Add AzuraCast multi-station notes
- [ ] Add Genius API setup instructions
- [ ] Document all environment variables

**Changes to README.md:**
```markdown
## 🚀 Production Deployment

### Reverb (WebSocket)
For production deployment, see [Reverb Deployment Guide](docs/REVERB_DEPLOYMENT.md)

### AzuraCast Multi-Station Setup
To configure multiple stations:
1. Configure each station in AzuraCast admin panel
2. Note station IDs
3. Update AZURACAST_STATION_ID in .env
4. Users can switch stations via /stations page

### Genius API Configuration
To enable lyrics:
1. Create account at https://genius.com/api-clients
2. Generate API token
3. Set GENIUS_API_TOKEN in .env
```

---

### Task 3.3: Create AzuraCast Integration Guide
**Priority:** 🟢 Low  
**Status:** [ ] Not Started  
**Effort:** 1 hour  
**Impact:** Helps users configure AzuraCast

**Subtasks:**
- [ ] Create `docs/AZURACAST_SETUP.md`
- [ ] Document API key generation
- [ ] Document required endpoints
- [ ] Document station configuration
- [ ] Document multi-server setup
- [ ] Add troubleshooting tips

---

## Phase 4: UI/UX Enhancements (Optional)

### Task 4.1: Enhance Multi-Station UI
**Priority:** 🟢 Low  
**Status:** [ ] Not Started  
**Effort:** 2-3 hours  
**Impact:** Better multi-station experience

**Subtasks:**
- [ ] Add station switcher dropdown to header
- [ ] Show current station in player
- [ ] Persist selected station in localStorage
- [ ] Update now playing when station changes
- [ ] Add station logos/branding

**Design Considerations:**
- Dropdown in header next to logo
- Show station name + tagline
- Smooth transition when switching
- Mobile-responsive design

---

### Task 4.2: Enhance Lyrics Monetization UI
**Priority:** 🟢 Low  
**Status:** [ ] Not Started  
**Effort:** 2-3 hours  
**Impact:** Better monetization flow

**Subtasks:**
- [ ] Create lyrics limit modal component
- [ ] Add countdown timer for unlock
- [ ] Add "Watch ad to unlock" button
- [ ] Add "Sign up for unlimited" call-to-action
- [ ] Show remaining views counter
- [ ] Add progress bar for guest limits

**Design Considerations:**
- Modern modal with gradient
- Clear messaging about limits
- Prominent sign-up button
- Non-intrusive ad option

---

## Phase 5: Advanced Features (Future)

### Task 5.1: Fine-Grained Permissions
**Priority:** 🟢 Low  
**Status:** [ ] Not Started  
**Effort:** 4-6 hours  
**Impact:** More granular access control

**Current State:** Role-based (admin, staff, DJ, etc.)  
**Proposed:** Permission-based (edit-news, manage-users, etc.)

**Subtasks:**
- [ ] Define permissions in seeder
- [ ] Create policy classes
- [ ] Update controllers to check permissions
- [ ] Update admin UI to show permissions
- [ ] Add tests for each permission

**Example:**
```php
// Instead of:
if (auth()->user()->hasRole('admin')) { }

// Use:
if (auth()->user()->can('edit-news')) { }
```

---

### Task 5.2: Observability Integration
**Priority:** 🟢 Low  
**Status:** [ ] Not Started  
**Effort:** 8-12 hours  
**Impact:** Better production monitoring

**Subtasks:**
- [ ] Add Laravel Telescope for local debugging
- [ ] Integrate Prometheus metrics
- [ ] Add custom metrics (cache hit ratio, API latency)
- [ ] Set up Grafana dashboards
- [ ] Configure alerting

---

### Task 5.3: PWA Features
**Priority:** 🟢 Low  
**Status:** [ ] Not Started  
**Effort:** 6-8 hours  
**Impact:** Better mobile experience

**Subtasks:**
- [ ] Enhance service worker
- [ ] Add offline page
- [ ] Implement background audio
- [ ] Add install prompts
- [ ] Test on iOS and Android

---

## Quick Reference: Effort Estimates

| Phase | Total Effort | Priority |
|-------|-------------|----------|
| Phase 1: Bug Fixes | ✅ 5 min | 🔴 Critical |
| Phase 2: Testing | 1.5-2 hours | 🟡 Medium |
| Phase 3: Documentation | 2.5-3.5 hours | 🟡 Medium |
| Phase 4: UI/UX | 4-6 hours | 🟢 Low |
| Phase 5: Advanced | 18-26 hours | 🟢 Low |

**Total for Medium Priority Tasks:** ~4-5.5 hours  
**Total for All Tasks:** ~26-37.5 hours

---

## Recommended Execution Order

### Sprint 1 (Immediate - 2 hours)
1. ✅ Fix theme bug (completed)
2. Add WebSocket integration tests
3. Add lyrics guest limit tests
4. Add permission boundary tests

### Sprint 2 (Short-term - 3 hours)
1. Create Reverb deployment guide
2. Enhance README with production notes
3. Create AzuraCast integration guide

### Sprint 3 (Optional - As Needed)
1. Enhance multi-station UI
2. Enhance lyrics monetization UI
3. Advanced features as desired

---

## Success Metrics

**After Phase 2 (Testing):**
- ✅ Test coverage > 85%
- ✅ All critical features tested
- ✅ Zero failing tests

**After Phase 3 (Documentation):**
- ✅ Production deployment documented
- ✅ All configuration guides complete
- ✅ README accuracy 100%

**After Phase 4 (UI/UX):**
- ✅ Multi-station switching functional
- ✅ Monetization flow user-friendly
- ✅ User experience polished

---

## Notes

- **Current Status:** Production-ready with minor gaps
- **Blocking Issues:** None ✅
- **Critical Path:** Phase 2 testing improvements
- **Quick Wins:** All of Phase 2 can be completed in < 2 hours

**Recommendation:** Focus on Phase 2 (testing) for immediate quality improvement, then Phase 3 (documentation) for better onboarding.
