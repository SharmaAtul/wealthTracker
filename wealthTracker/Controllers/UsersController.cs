using Microsoft.AspNet.Identity;
using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Entity;
using System.Data.Entity.Infrastructure;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Security.Claims;
using System.Threading.Tasks;
using System.Web.Http;
using System.Web.Http.Description;
using wealthTracker.DAL;
using wealthTracker.Models;
using wealthTracker.Providers;

namespace wealthTracker.Controllers
{
    //AMUWpKW/bhnAVPBZe8c6O11YQ9XSnAXjD0WHiVTuMNCywwZ7VKzmV2RYdp/NH51SpQ==
    [Authorize]
    [RoutePrefix("api/users")]
    public class UsersController : ApiController
    {
        private WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();

        private AuthRepository _repo = null;
        public UsersController()
        {
            _repo = new AuthRepository();
        }

        // GET: api/Users
        public IQueryable<IUser> GetUsers()
        {
            return db.AppUsers;
        }

        //// GET: api/Users/5
        //[ResponseType(typeof(User))]
        //public IHttpActionResult GetUser(string id)
        //{
        //    User user = db.Users.Find(id);
        //    if (user == null)
        //    {
        //        return NotFound();
        //    }

        //    return Ok(user);
        //}

        //// PUT: api/Users/5
        //[ResponseType(typeof(void))]
        //public IHttpActionResult PutUser(string id, User user)
        //{
        //    if (!ModelState.IsValid)
        //    {
        //        return BadRequest(ModelState);
        //    }

        //    if (id != user.UserEmail)
        //    {
        //        return BadRequest();
        //    }

        //    db.Entry(user).State = EntityState.Modified;

        //    try
        //    {
        //        db.SaveChanges();
        //    }
        //    catch (DbUpdateConcurrencyException)
        //    {
        //        if (!UserExists(id))
        //        {
        //            return NotFound();
        //        }
        //        else
        //        {
        //            throw;
        //        }
        //    }

        //    return StatusCode(HttpStatusCode.NoContent);
        //}

        //// POST: api/Users
        //[ResponseType(typeof(User))]
        //public IHttpActionResult PostUser(User user)
        //{
        //    if (!ModelState.IsValid)
        //    {
        //        return BadRequest(ModelState);
        //    }

        //    db.Users.Add(user);

        //    try
        //    {
        //        db.SaveChanges();
        //    }
        //    catch (DbUpdateException)
        //    {
        //        if (UserExists(user.UserEmail))
        //        {
        //            return Conflict();
        //        }
        //        else
        //        {
        //            throw;
        //        }
        //    }

        //    return CreatedAtRoute("DefaultApi", new { id = user.UserEmail }, user);
        //}

        //// DELETE: api/Users/5
        //[ResponseType(typeof(User))]
        //public IHttpActionResult DeleteUser(string id)
        //{
        //    User user = db.Users.Find(id);
        //    if (user == null)
        //    {
        //        return NotFound();
        //    }

        //    db.Users.Remove(user);
        //    db.SaveChanges();

        //    return Ok(user);
        //}

        public class UserData
        {
            public int totalCount;
            public List<AppUser> records;
        }

        [Route("getAll/{selectedClientId}/{currentPage}/{pageSize}")]
        public IHttpActionResult GetAll(string selectedClientId, string currentPage, string pageSize)
        {
            //if user is not admin return..
            var identity = User.Identity as ClaimsIdentity;
            string userRole = identity.FindFirst(ClaimTypes.Role).Value;
            string clientId = identity.FindFirst("ClientId").Value;

            if (userRole != "admin")
            {
                //ModelState.AddModelError("InvalidAccess", "You are not authorized !");
                return BadRequest("UnauthorizedAccess");
            }

            UserData responseData = new UserData();
            AppUser users = new AppUser();
            int totalCount = 0;

            List<AppUser> lstUsers = users.GetAll(int.Parse(clientId), int.Parse(selectedClientId), int.Parse(currentPage), int.Parse(pageSize), out totalCount);
            if (lstUsers != null)
            {
                foreach (AppUser user in lstUsers)
                {
                    user.UserPassword = MyPasswordHasher.Decrypt(user.UserPassword, true);
                }
            }

            responseData.records = lstUsers;
            responseData.totalCount = totalCount;
            return Ok(responseData);
        }

        [AllowAnonymous]
        [Route("register")]
        // POST api/users/Register
        public async Task<IHttpActionResult> Register(AppUser user)
        {
            //if user is not admin return..
            var identity = User.Identity as ClaimsIdentity;
            string userRole = identity.FindFirst(ClaimTypes.Role).Value;
            if (userRole != "admin")
            {
                //ModelState.AddModelError("InvalidAccess", "You are not authorized !");
                return BadRequest("UnauthorizedAccess");
            }

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }
            IdentityResult result = new IdentityResult();

            //UserManager<AppUser> userManager = new UserManager<AppUser>(new UserStoreService<IdentityUser>());
            //var result = userManager.ChangePassword(_User.Id, userNewPassword, userView.Password);
            //return RedirectToAction("Index", "ConfigUser");

            if (user.UserID == 0)
            {
                result = await _repo.RegisterUser(user);
                IHttpActionResult errorResult = GetErrorResult(result);

                if (errorResult != null)
                {
                    return errorResult;
                }
            }
            user.UserType = "client";
            user.UserPassword = MyPasswordHasher.Encrypt(user.UserPassword, true);
            user.Update();

            return Ok();
        }

        protected override void Dispose(bool disposing)
        {
            if (disposing)
            {
                _repo.Dispose();
            }

            base.Dispose(disposing);
        }

        private IHttpActionResult GetErrorResult(IdentityResult result)
        {
            if (result == null)
            {
                return InternalServerError();
            }

            if (!result.Succeeded)
            {
                if (result.Errors != null)
                {
                    foreach (string error in result.Errors)
                    {
                        ModelState.AddModelError("", error);
                    }
                }

                if (ModelState.IsValid)
                {
                    // No ModelState errors are available to send, so just return an empty BadRequest.
                    return BadRequest();
                }

                return BadRequest(ModelState);
            }

            return null;
        }
    

    //private bool UserExists(string id)
    //    {
    //        return db.Users.Count(e => e.UserEmail == id) > 0;
    //    }
    }
}