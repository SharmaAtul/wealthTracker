using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Entity;
using System.Data.Entity.Infrastructure;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Web.Http;
using System.Web.Http.Description;
using wealthTracker.DAL;
using wealthTracker.Models;

namespace wealthTracker.Controllers
{
    [Authorize]
    public class UserDetailsController : ApiController
    {
        private WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();

        // GET: api/UserDetails
        public IQueryable<UserDetail> GetUserDetail()
        {
            return db.UserDetail;
        }

        // GET: api/UserDetails/5
        [ResponseType(typeof(UserDetail))]
        public IHttpActionResult GetUserDetail(string id)
        {
            UserDetail userDetail = db.UserDetail.Find(id);
            if (userDetail == null)
            {
                return NotFound();
            }

            return Ok(userDetail);
        }

        // PUT: api/UserDetails/5
        [ResponseType(typeof(void))]
        public IHttpActionResult PutUserDetail(string id, UserDetail userDetail)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (id != userDetail.TestKey)
            {
                return BadRequest();
            }

            db.Entry(userDetail).State = EntityState.Modified;

            try
            {
                db.SaveChanges();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!UserDetailExists(id))
                {
                    return NotFound();
                }
                else
                {
                    throw;
                }
            }

            return StatusCode(HttpStatusCode.NoContent);
        }

        // POST: api/UserDetails
        [ResponseType(typeof(UserDetail))]
        public IHttpActionResult PostUserDetail(UserDetail userDetail)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            db.UserDetail.Add(userDetail);

            try
            {
                db.SaveChanges();
            }
            catch (DbUpdateException)
            {
                if (UserDetailExists(userDetail.TestKey))
                {
                    return Conflict();
                }
                else
                {
                    throw;
                }
            }

            return CreatedAtRoute("DefaultApi", new { id = userDetail.TestKey }, userDetail);
        }

        // DELETE: api/UserDetails/5
        [ResponseType(typeof(UserDetail))]
        public IHttpActionResult DeleteUserDetail(string id)
        {
            UserDetail userDetail = db.UserDetail.Find(id);
            if (userDetail == null)
            {
                return NotFound();
            }

            db.UserDetail.Remove(userDetail);
            db.SaveChanges();

            return Ok(userDetail);
        }

        protected override void Dispose(bool disposing)
        {
            if (disposing)
            {
                db.Dispose();
            }
            base.Dispose(disposing);
        }

        private bool UserDetailExists(string id)
        {
            return db.UserDetail.Count(e => e.TestKey == id) > 0;
        }
    }
}