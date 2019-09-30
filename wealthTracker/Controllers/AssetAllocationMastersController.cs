using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Entity;
using System.Data.Entity.Infrastructure;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Security.Claims;
using System.Web.Http;
using System.Web.Http.Description;
using wealthTracker.DAL;
using wealthTracker.Models;

namespace wealthTracker.Controllers
{
    [Authorize]
    [RoutePrefix("api/assetAllocationMasters")]
    public class AssetAllocationMastersController : ApiController
    {
        private WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();

        // GET: api/AssetAllocationMasters
        public IHttpActionResult GetAssetAllocationMasters()
        {
            //if user is not admin return..
            var identity = User.Identity as ClaimsIdentity;
            string userRole = identity.FindFirst(ClaimTypes.Role).Value;
            if (userRole != "admin")
            {
                //ModelState.AddModelError("InvalidAccess", "You are not authorized !");
                return BadRequest("UnauthorizedAccess");
            }

            return Ok(db.AssetAllocationMasters);
        }

        // GET: api/AssetAllocationMasters/5
        [ResponseType(typeof(AssetAllocationMaster))]
        public IHttpActionResult GetAssetAllocationMaster(string id)
        {
            //if user is not admin return..
            var identity = User.Identity as ClaimsIdentity;
            string userRole = identity.FindFirst(ClaimTypes.Role).Value;
            if (userRole != "admin")
            {
                //ModelState.AddModelError("InvalidAccess", "You are not authorized !");
                return BadRequest("UnauthorizedAccess");
            }

            AssetAllocationMaster assetAllocationMaster = db.AssetAllocationMasters.Find(id);
            if (assetAllocationMaster == null)
            {
                return NotFound();
            }

            return Ok(assetAllocationMaster);
        }

        public class RiskTitleRename
        {
            public string OldName { get; set; }
            public string NewName { get; set; }
        }

        [HttpPost]
        [Route("updateTitle")]
        public IHttpActionResult UpdateTitle(RiskTitleRename param)
        {
            var identity = User.Identity as ClaimsIdentity;
            string userRole = identity.FindFirst(ClaimTypes.Role).Value;
            if (userRole != "admin")
            {
                //ModelState.AddModelError("InvalidAccess", "You are not authorized !");
                return BadRequest("UnauthorizedAccess");
            }
            int result = 0;

            AssetAllocationMaster assetAllocationMaster = new AssetAllocationMaster();
            result = assetAllocationMaster.Update(param.OldName, param.NewName);

            string message = "SUCCESS";

            if (result == -1)
            {
                if (param.OldName == "")
                {
                    //Add New
                    message = "ALREADY_EXISTS";

                }
                else if (param.NewName == "")
                {
                    //Add New
                    message = "IN_USE";

                }
                return Ok(message);
            }
            else
            {
                return Ok(db.AssetAllocationMasters);
            }
        }

        // PUT: api/AssetAllocationMasters/5
        [ResponseType(typeof(void))]
        public IHttpActionResult PutAssetAllocationMaster(string id, AssetAllocationMaster assetAllocationMaster)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (id != assetAllocationMaster.RiskProfileType)
            {
                return BadRequest();
            }

            db.Entry(assetAllocationMaster).State = EntityState.Modified;

            try
            {
                db.SaveChanges();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!AssetAllocationMasterExists(id))
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

        // POST: api/AssetAllocationMasters
        [ResponseType(typeof(AssetAllocationMaster))]
        public IHttpActionResult PostAssetAllocationMaster(List<AssetAllocationMaster> assetAllocationMasters)
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

            foreach (AssetAllocationMaster assetAllocationMaster in assetAllocationMasters)
            {
                AssetAllocationMaster assetAlloc = db.AssetAllocationMasters.Find(assetAllocationMaster.RiskProfileType);
                
                assetAlloc.AttractiveAssets = assetAllocationMaster.AttractiveAssets;
                assetAlloc.AusFixedInterest = assetAllocationMaster.AusFixedInterest;
                assetAlloc.AusShares = assetAllocationMaster.AusShares;
                assetAlloc.Cash = assetAllocationMaster.Cash;
                assetAlloc.IntFixedInterest = assetAllocationMaster.IntFixedInterest;
                assetAlloc.IntShares = assetAllocationMaster.IntShares;
                assetAlloc.Property = assetAllocationMaster.Property;
                assetAlloc.ApplySort = assetAllocationMaster.ApplySort;
                //db.AssetAllocationMasters.Add(assetAllocationMaster);

                try
                {
                    db.SaveChanges();
                }
                catch (DbUpdateException)
                {
                    if (AssetAllocationMasterExists(assetAllocationMaster.RiskProfileType))
                    {
                        return Conflict();
                    }
                    else
                    {
                        throw;
                    }
                }
            }

            return Ok(true);
            //return CreatedAtRoute("DefaultApi", new { id = assetAllocationMaster.RiskProfileType }, assetAllocationMaster);
        }

        // DELETE: api/AssetAllocationMasters/5
        [ResponseType(typeof(AssetAllocationMaster))]
        public IHttpActionResult DeleteAssetAllocationMaster(string id)
        {
            AssetAllocationMaster assetAllocationMaster = db.AssetAllocationMasters.Find(id);
            if (assetAllocationMaster == null)
            {
                return NotFound();
            }

            db.AssetAllocationMasters.Remove(assetAllocationMaster);
            db.SaveChanges();

            return Ok(assetAllocationMaster);
        }

        protected override void Dispose(bool disposing)
        {
            if (disposing)
            {
                db.Dispose();
            }
            base.Dispose(disposing);
        }

        private bool AssetAllocationMasterExists(string id)
        {
            return db.AssetAllocationMasters.Count(e => e.RiskProfileType == id) > 0;
        }
    }
}