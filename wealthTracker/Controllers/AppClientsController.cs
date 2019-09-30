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
    [RoutePrefix("api/appClients")]
    public class AppClientsController : ApiController
    {
        // GET: AppClients
        private WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();

        private AuthRepository _repo = null;
        public AppClientsController()
        {
            _repo = new AuthRepository();
        }

        [Route("getAppClientList/{currentPage}/{pageSize}")]
        public IHttpActionResult GetAllNameId(string currentPage, string pageSize)
        {
            //if user is not admin return..
            var identity = User.Identity as ClaimsIdentity;
            string userRole = identity.FindFirst(ClaimTypes.Role).Value;
            string clientId = identity.FindFirst("ClientId").Value;

            if (userRole != "admin")
            {
                return BadRequest("UnauthorizedAccess");
            }

            AppClient appClient = new AppClient();
            int totalCount = 0;

            var result = AppClient.GetAll(int.Parse(currentPage), pageSize == "0" ? 999 : int.Parse(pageSize), out totalCount)
                .Select(i => new { i.Id, i.Name });

            return Ok(new { records = result, totalCount = totalCount, defaultClient= clientId });
        }
    }
}