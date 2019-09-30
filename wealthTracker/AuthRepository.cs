using Microsoft.AspNet.Identity;
using Microsoft.AspNet.Identity.EntityFramework;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using System.Web;
using wealthTracker.DAL;
using wealthTracker.Models;
using wealthTracker.Providers;

namespace wealthTracker
{
    public class AuthRepository : IDisposable
    {
        private WealthTrackerIdentityDB _ctx;

        private UserManager<AppUser> _userManager;

        public AuthRepository()
        {
            _ctx = new WealthTrackerIdentityDB();
            _userManager = new UserManager<AppUser>(new UserStoreService());
            _userManager.PasswordHasher = new MyPasswordHasher();
            _userManager.UserValidator = new UserValidator<AppUser>(_userManager) { AllowOnlyAlphanumericUserNames = false };
        }

        public async Task<IdentityResult> RegisterUser(AppUser userModel)
        {
            AppUser user = new AppUser
            {
                UserName = userModel.UserEmail,
                UserPassword = userModel.UserPassword
            };
            
            var result = await _userManager.CreateAsync(user, userModel.UserPassword);

            return result;
        }

        public async Task<AppUser> FindUser(string userName, string password)
        {
            AppUser user = await _userManager.FindAsync(userName, password);

            return user;
        }

        public void Dispose()
        {
            _ctx.Dispose();
            _userManager.Dispose();

        }
    }
}