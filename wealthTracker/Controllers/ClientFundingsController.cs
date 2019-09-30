using Microsoft.Office.Interop.Excel;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Entity;
using System.Data.Entity.Infrastructure;
using System.Data.OleDb;
using System.Globalization;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Runtime.InteropServices;
using System.Security.Claims;
using System.Threading.Tasks;
using System.Web;
using System.Web.Http;
using System.Web.Http.Description;
using wealthTracker.DAL;
using wealthTracker.Models;
using wealthTracker.Providers;

namespace wealthTracker.Controllers
{
    [Authorize]
    [RoutePrefix("api/clientFundings")]
    public class ClientFundingsController : ApiController
    {
        private WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();

        // GET: api/ClientFundings
        public IQueryable<ClientFunding> GetClientFundings()
        {
            return db.ClientFundings;
        }

        public class ClientFundingHistoryParam {
            public int ClientID { get; set; }
            public int CurrentPage { get; set; }
            public int PageSize { get; set; }
        }
        public class RiskProfileTypes
        {
            public string RiskProfileType { get; set; }
            public string DisplayField { get; set; }
        }
        public class ClientFundingData
        {
            public int totalCount;
            public List<ClientFunding> records;
            public AppUser userDetail;
            
            //public ClientFunding lastFunding;
        }

        [HttpPost]
        [Route("getAll")]
        public IHttpActionResult GetAll(ClientFundingHistoryParam param)
        {
            var identity = User.Identity as ClaimsIdentity;
            string userRole = identity.FindFirst(ClaimTypes.Role).Value;
            if (userRole != "admin")
            {
                //ModelState.AddModelError("InvalidAccess", "You are not authorized !");
                return BadRequest("UnauthorizedAccess");
            }

            ClientFundingData responseData = new ClientFundingData();
            ClientFunding oclientFunding = new ClientFunding();

            

            responseData.userDetail = AppUser.GetById(param.ClientID);
            int totalCount = 0;
            responseData.records = oclientFunding.GetAll(param.ClientID, param.CurrentPage, param.PageSize, out totalCount);
            responseData.totalCount = totalCount;
            
            return Ok(responseData);
        }

        public class AppUserBasicData {
            public string UserEmail { get; set; }
            public string FirstName { get; set; }
            public string LastName { get; set; }
            public DateTime? DOB { get; set; }
            public string SpouseFirstName { get; set; }
            public string SpouseLastName { get; set; }
            public DateTime? SpouseDOB { get; set; }
        }

        public class ClientFundingDetails{
            public ClientFunding funding;
            public AppUserBasicData user;
            public List<RiskProfileTypes> riskProfiles = new List<RiskProfileTypes>();
            public List<ProjectedData> listProjectedData = new List<ProjectedData>();
            public decimal ExpectedTotalFund { get; set; }
        }
        
        [Route("{id:int}/{fundingId:int}")]
        public IHttpActionResult GetClientFunding(int id,int fundingId)
        {
            //if user is not admin return..
            var identity = User.Identity as ClaimsIdentity;
            string userRole = identity.FindFirst(ClaimTypes.Role).Value;
            int userId = int.Parse(identity.FindFirst(ClaimTypes.SerialNumber).Value);
            if (userRole != "admin" && userId != id)
            {
                //ModelState.AddModelError("InvalidAccess", "You are not authorized !");
                return BadRequest("UnauthorizedAccess");
            }

            ClientFundingDetails clientFundingDetails = new ClientFundingDetails();

            ClientFunding clientFunding = new ClientFunding();

            foreach (AssetAllocationMaster assetMaster in db.AssetAllocationMasters.ToList<AssetAllocationMaster>())
                clientFundingDetails.riskProfiles.Add(new RiskProfileTypes
                {
                    RiskProfileType = assetMaster.RiskProfileType,
                    DisplayField = assetMaster.DisplayField
                });

            AppUser user = AppUser.GetById(id);
            clientFundingDetails.user = new AppUserBasicData();

            clientFundingDetails.user.UserEmail = user.UserEmail;
            clientFundingDetails.user.FirstName = user.FirstName;
            clientFundingDetails.user.LastName = user.LastName;
            clientFundingDetails.user.DOB = user.DOB;
            clientFundingDetails.user.SpouseFirstName = user.SpouseFirstName;
            clientFundingDetails.user.SpouseLastName = user.SpouseLastName;
            clientFundingDetails.user.SpouseDOB = user.SpouseDOB;

            if (fundingId == 0)
                clientFundingDetails.funding = clientFunding.GetLatestClientFunding(clientFundingDetails.user.UserEmail);
            else
            {
                clientFundingDetails.funding = clientFunding.GetByFundingId(fundingId);

                AssetAllocationMaster assetAllocationMaster = db.AssetAllocationMasters.Find(clientFundingDetails.funding.RiskProfile);

                clientFundingDetails.funding.ExpectedAttractiveAssets = assetAllocationMaster.AttractiveAssets;
                clientFundingDetails.funding.ExpectedAusFixedInterest = assetAllocationMaster.AusFixedInterest;
                clientFundingDetails.funding.ExpectedAusShares = assetAllocationMaster.AusShares;
                clientFundingDetails.funding.ExpectedCash = assetAllocationMaster.Cash;
                clientFundingDetails.funding.ExpectedIntFixedInterest = assetAllocationMaster.IntFixedInterest;
                clientFundingDetails.funding.ExpectedIntShares = assetAllocationMaster.IntShares;
                clientFundingDetails.funding.ExpectedProperty = assetAllocationMaster.Property;

                ProjectedData projections = new ProjectedData();
                clientFundingDetails.listProjectedData = projections.GetProjectedDataByClientId(id);
                ProjectedData clientExpectedProjectData = clientFundingDetails.listProjectedData.Find(i => i.Year == clientFundingDetails.funding.FundingYear);

                if (clientExpectedProjectData != null)
                    clientFundingDetails.ExpectedTotalFund = clientExpectedProjectData.Value;
                else
                    clientFundingDetails.ExpectedTotalFund = 0;

            }
            if (clientFundingDetails.funding == null)
            {
                return NotFound();
            }

            return Ok(clientFundingDetails);
        }

        public class DashboardData {
            public List<DashboardReportData> AssetClassesReportData = new List<DashboardReportData>();
            public List<DashboardReportData> NetWealthReportData = new List<DashboardReportData>();
            public List<DashboardReportData> AssetClassesComparisonReportData = new List<DashboardReportData>();
            public List<DashboardReportData> NetWealthComparisonReportData = new List<DashboardReportData>();
            public AppUser userData = new AppUser();
            public ClientFunding lastFunding = new ClientFunding();
            public decimal ExpectedTotalFund { get; set; }
        }

        public class ComparisonReportData
        {
            public string Color { get; set; }
            public string Key { get; set; }
            public bool Area { get; set; }
            public List<List<int>> Values { get; set; }
        }

        [HttpGet]
        [Route("getProjections/{id:int}")]
        public IHttpActionResult GetProjections(int id)
        {
            //if user is not admin return..
            var identity = User.Identity as ClaimsIdentity;
            string userRole = identity.FindFirst(ClaimTypes.Role).Value;
            int userId = int.Parse(identity.FindFirst(ClaimTypes.SerialNumber).Value);
            if (userRole != "admin" && userId != id)
            {
                //ModelState.AddModelError("InvalidAccess", "You are not authorized !");
                return BadRequest("UnauthorizedAccess");
            }

            ProjectedData projections = new ProjectedData();
            
            return Ok(projections.GetProjectedDataByClientId(id));
        }

        [HttpGet]
        [Route("getDashboardData/{id:int}")]
        public IHttpActionResult GetDashboardData(int id)
        {
            //if user is not admin return..
            var identity = User.Identity as ClaimsIdentity;
            string userRole = identity.FindFirst(ClaimTypes.Role).Value;
            int userId = int.Parse(identity.FindFirst(ClaimTypes.SerialNumber).Value);
            if (userRole != "admin" && userId != id)
            {
                //ModelState.AddModelError("InvalidAccess", "You are not authorized !");
                return BadRequest("UnauthorizedAccess");
            }
            DashboardData dbData = new DashboardData();
            ClientFunding clientFunding = new ClientFunding();
            Dictionary<string, List<DashboardReportData>> assetClassesData = clientFunding.GetAssetClassesReportData(id);
            List<DashboardReportData> assetClassesReportData = assetClassesData["All"];
            List<DashboardReportData> assetClassesComparisonReportData = assetClassesData["Growth"];
            Dictionary<string, List<DashboardReportData>> assetWealthData = clientFunding.GetNetWealthReportData(id);
            List<DashboardReportData> netWealthReportData = assetWealthData["All"];
            List<DashboardReportData> netWealthComparisonReportData = assetWealthData["Net"];

            foreach (DashboardReportData item in assetClassesReportData)
            {
                switch (item.key)
                {
                    case "Attractive Assets":
                        item.color = "darkred"; break;
                    case "Int. Shares":
                    case "Other Entities Assets":
                        item.color = "red"; break;
                    case "Aus. Shares":
                    case "Pension Assets":
                        item.color = "orange"; break;
                    case "Property":
                    case "Super Assets":
                        item.color = "yellow"; break;
                    case "Int. Fixed Interest":
                        item.color = "lightgreen"; break;
                    case "Aus. Fixed Interest":
                    case "Personal Assets":
                        item.color = "green"; break;
                    case "Cash":
                        item.color = "darkgreen"; break;
                }
            }

            foreach (DashboardReportData item in netWealthReportData)
            {
                switch (item.key)
                {
                    case "Other Entities Assets":
                        item.color = "red"; break;
                    case "Pension Assets":
                        item.color = "orange"; break;
                    case "Super Assets":
                        item.color = "yellow"; break;
                    case "Personal Assets":
                        item.color = "green"; break;
                }
            }

            dbData.AssetClassesReportData = assetClassesReportData;
            dbData.NetWealthReportData = netWealthReportData;
            dbData.AssetClassesComparisonReportData = assetClassesComparisonReportData;
            dbData.NetWealthComparisonReportData = netWealthComparisonReportData;

            dbData.userData = AppUser.GetById(id);
            dbData.lastFunding = clientFunding.GetLatestClientFunding(dbData.userData.UserEmail);

            AssetAllocationMaster assetAllocationMaster = db.AssetAllocationMasters.Find(dbData.lastFunding.RiskProfile);
            if (assetAllocationMaster != null)
            {
                dbData.lastFunding.ExpectedAttractiveAssets = assetAllocationMaster.AttractiveAssets;
                dbData.lastFunding.ExpectedAusFixedInterest = assetAllocationMaster.AusFixedInterest;
                dbData.lastFunding.ExpectedAusShares = assetAllocationMaster.AusShares;
                dbData.lastFunding.ExpectedCash = assetAllocationMaster.Cash;
                dbData.lastFunding.ExpectedIntFixedInterest = assetAllocationMaster.IntFixedInterest;
                dbData.lastFunding.ExpectedIntShares = assetAllocationMaster.IntShares;
                dbData.lastFunding.ExpectedProperty = assetAllocationMaster.Property;
            }

            dbData.ExpectedTotalFund = ProjectedData.GetByUserIdAndYear(id, dbData.lastFunding.FundingYear);

            return Ok(dbData);
        }

        [HttpPost]
        [Route("uploadExcel")]
        public async Task<HttpResponseMessage> uploadExcel()
        {
            if (!Request.Content.IsMimeMultipartContent())
            {
                this.Request.CreateResponse(HttpStatusCode.UnsupportedMediaType);
            }

            var provider = GetMultipartProvider();
            var result = await Request.Content.ReadAsMultipartAsync(provider);

            //string strFileName = result.FileData.First().Headers.ContentDisposition.FileName.TrimStart('"').TrimEnd('"');
            //string[] fileSplits = strFileName.Split('.');
            //string fileExtension = fileSplits[fileSplits.Length - 1];
            //// On upload, files are given a generic name like "BodyPart_26d6abe1-3ae1-416a-9429-b35f15e6e5d5"
            //// so this is how you can get the original file name
            //var originalFileName = GetDeserializedFileName(result.FileData.First());

            // uploadedFileInfo object will give you some additional stuff like file length,
            // creation time, directory name, a few filesystem methods etc..
            var uploadedFileInfo = new FileInfo(result.FileData.First().LocalFileName);
            //File.Copy(uploadedFileInfo.FullName, uploadedFileInfo.FullName + "." + fileExtension);
            // Remove this line as well as GetFormData method if you're not
            // sending any form data with your upload request

            List<int> listRowsToPars = new List<int>();
            listRowsToPars.Add(int.Parse(result.FormData["rowNumYear"]));
            listRowsToPars.Add(int.Parse(result.FormData["rowNumNetWealth"]));
            int fundingId = int.Parse(result.FormData["fundingId"]);

            string[] lastProjectDt = result.FormData["lastProjectionDate"].Split('/');
            DateTime lastProjectionDate = new DateTime(int.Parse(lastProjectDt[2]), int.Parse(lastProjectDt[1]), int.Parse(lastProjectDt[0]));

            int clientID = int.Parse(result.FormData["clientID"]);

            try
            {
                ReadExcel(clientID, uploadedFileInfo.FullName, listRowsToPars, lastProjectionDate, fundingId);
            }
            catch (Exception ex)
            {
                    return this.Request.CreateResponse(HttpStatusCode.OK, new { ex });
            }

            //var uploadedFileInfoXlsx = new FileInfo(result.FileData.First().LocalFileName + "." + fileExtension);

            //uploadedFileInfo.Delete();
            //uploadedFileInfoXlsx.Delete();

            // Through the request response you can return an object to the Angular controller
        // You will be able to access this in the .success callback through its data attribute
        // If you want to send something to the .error callback, use the HttpStatusCode.BadRequest instead
            var returnData = "Success";
            return this.Request.CreateResponse(HttpStatusCode.OK, new { returnData });
        }

        private void ReadExcel(int clientID, string fileName, List<int> listRowsToParse, DateTime projectionDate, int fundingId)
        {
            List<int> listYearData = new List<int>();
            List<decimal> listProjectedNetWealth = new List<decimal>();

            List<string> columns = new List<string>();
            int currentRow = 0;
            using (var reader = new CsvFileReader(fileName))
            {
                while (reader.ReadRow(columns))
                {
                    if(currentRow == listRowsToParse[0]-1)
                    {
                        for (int colIndex = 1; colIndex < columns.Count - 1; colIndex++)
                        {
                            string year = columns[colIndex].ToString();
                            if (year == "")
                                year = "0";
                            
                            listYearData.Add(int.Parse(columns[colIndex]));
                        }
                    }
                    else if (currentRow == listRowsToParse[1]-1)
                    {
                        for (int colIndex = 1; colIndex < columns.Count - 1; colIndex++)
                        {
                            string value = columns[colIndex].ToString();
                            if (value == "")
                                value = "0";

                            listProjectedNetWealth.Add(int.Parse(columns[colIndex]));
                        }

                        break;
                    }

                    currentRow++;
                    continue;
                }
            }
            
            string csvYears = String.Join(",", listYearData.Select(x => x.ToString()).ToArray());
            string csvValues = String.Join(",", listProjectedNetWealth.Select(x => x.ToString()).ToArray());

            ProjectedData projections = new ProjectedData();
            projections.ImportProjectedData(clientID, csvYears, csvValues, projectionDate, fundingId);
            
        }
        
        // You could extract these two private methods to a separate utility class since
        // they do not really belong to a controller class but that is up to you
        private MultipartFormDataStreamProvider GetMultipartProvider()
        {
            // IMPORTANT: replace "(tilde)" with the real tilde character
            // (our editor doesn't allow it, so I just wrote "(tilde)" instead)
            var uploadFolder = "~/App_Data/Tmp/FileUploads"; // you could put this to web.config
            var root = HttpContext.Current.Server.MapPath(uploadFolder);
            Directory.CreateDirectory(root);
            return new MultipartFormDataStreamProvider(root);
        }

        // Extracts Request FormatData as a strongly typed model
        private object GetFormData<T>(MultipartFormDataStreamProvider result)
        {
            if (result.FormData.HasKeys())
            {
                var unescapedFormData = Uri.UnescapeDataString(result.FormData
                    .GetValues(0).FirstOrDefault() ?? String.Empty);
                if (!String.IsNullOrEmpty(unescapedFormData))
                    return JsonConvert.DeserializeObject<T>(unescapedFormData);
            }

            return null;
        }

        private string GetDeserializedFileName(MultipartFileData fileData)
        {
            var fileName = GetFileName(fileData);
            return JsonConvert.DeserializeObject(fileName).ToString();
        }

        public string GetFileName(MultipartFileData fileData)
        {
            return fileData.Headers.ContentDisposition.FileName;
        }

        [ResponseType(typeof(void))]
        public IHttpActionResult PutClientFunding(int id, ClientFunding clientFunding)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (id != clientFunding.ClientFundingID)
            {
                return BadRequest();
            }

            db.Entry(clientFunding).State = EntityState.Modified;

            try
            {
                db.SaveChanges();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!ClientFundingExists(clientFunding.UserEmail, clientFunding.FundingYear, clientFunding.FundingMonth))
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

        // POST: api/ClientFundings
        [ResponseType(typeof(ClientFunding))]
        public IHttpActionResult PostClientFunding(ClientFunding clientFunding)
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

            if (clientFunding.ClientFundingID == 0) {
                clientFunding.FundingYear = clientFunding.FundingPeriod.Value.Year;
                clientFunding.FundingMonth = clientFunding.FundingPeriod.Value.Month;
                
                db.ClientFundings.Add(clientFunding);
            }
            else
            {
                ClientFunding oclientFunding = db.ClientFundings.Find(clientFunding.UserEmail, clientFunding.FundingPeriod.Value.Year, clientFunding.FundingPeriod.Value.Month);
                if (oclientFunding == null) {
                    clientFunding.FundingYear = clientFunding.FundingPeriod.Value.Year;
                    clientFunding.FundingMonth = clientFunding.FundingPeriod.Value.Month;

                    db.ClientFundings.Add(clientFunding);
                }
                else
                {
                    oclientFunding.RiskProfile = clientFunding.RiskProfile;
                    oclientFunding.FinancialIndependencyAge = clientFunding.FinancialIndependencyAge;
                    oclientFunding.SumLeft = clientFunding.SumLeft;
                    oclientFunding.LifestyleObjectiveAt = clientFunding.LifestyleObjectiveAt;
                    oclientFunding.AnnualLeavingOff = clientFunding.AnnualLeavingOff;
                    oclientFunding.LastingFor = clientFunding.LastingFor;
                    oclientFunding.LeaveForState = clientFunding.LeaveForState;

                    oclientFunding.ActualAttractiveAssets = clientFunding.ActualAttractiveAssets;
                    oclientFunding.ActualAusFixedInterest = clientFunding.ActualAusFixedInterest;
                    oclientFunding.ActualAusShares = clientFunding.ActualAusShares;
                    oclientFunding.ActualCash = clientFunding.ActualCash;
                    oclientFunding.ActualIntFixedInterest = clientFunding.ActualIntFixedInterest;
                    oclientFunding.ActualIntShares = clientFunding.ActualIntShares;
                    oclientFunding.ActualProperty = clientFunding.ActualProperty;
                    oclientFunding.ExpectedAttractiveAssets = clientFunding.ExpectedAttractiveAssets;
                    oclientFunding.ExpectedAusFixedInterest = clientFunding.ExpectedAusFixedInterest;
                    oclientFunding.ExpectedAusShares = clientFunding.ExpectedAusShares;
                    oclientFunding.ExpectedCash = clientFunding.ExpectedCash;
                    oclientFunding.ExpectedIntFixedInterest = clientFunding.ExpectedIntFixedInterest;
                    oclientFunding.ExpectedIntShares = clientFunding.ExpectedIntShares;
                    oclientFunding.ExpectedProperty = clientFunding.ExpectedProperty;
                    oclientFunding.PersonalAssets = clientFunding.PersonalAssets;
                    oclientFunding.SuperAssets = clientFunding.SuperAssets;
                    oclientFunding.PensionAssets = clientFunding.PensionAssets;
                    
                    oclientFunding.OtherEntitiesAssets = clientFunding.OtherEntitiesAssets;
                    oclientFunding.TotalAssets = clientFunding.TotalAssets;
                    oclientFunding.AlternativeAssetDetails = clientFunding.AlternativeAssetDetails;
                    oclientFunding.PersonalAssetDetails = clientFunding.PersonalAssetDetails;
                    oclientFunding.SuperAssetDetails = clientFunding.SuperAssetDetails;
                    oclientFunding.PensionAssetDetails = clientFunding.PensionAssetDetails;
                    oclientFunding.OtherEntitiesAssetDetails = clientFunding.OtherEntitiesAssetDetails;
                    oclientFunding.LastProjectionDate = clientFunding.LastProjectionDate;
                    oclientFunding.Conclusion1 = clientFunding.Conclusion1;
                    oclientFunding.Conclusion2 = clientFunding.Conclusion2;
                    oclientFunding.Conclusion3 = clientFunding.Conclusion3;
                }
            }

            try
            {
                db.SaveChanges();
            }
            catch (DbUpdateException)
            {
                if (ClientFundingExists(clientFunding.UserEmail, clientFunding.FundingYear, clientFunding.FundingMonth))
                {
                    return Conflict();
                }
                else
                {
                    throw;
                }
            }

            return CreatedAtRoute("DefaultApi", new { id = clientFunding.ClientFundingID }, clientFunding);
        }

        // DELETE: api/ClientFundings/5
        //[ResponseType(typeof(ClientFunding))]
        [HttpGet]
        [Route("deleteClientFunding/{id:int}")]
        public IHttpActionResult DeleteClientFunding(int id)
        {
            ClientFunding clientFunding = new ClientFunding();
            bool result = clientFunding.Delete(id);
            
            return Ok(result);
        }

        protected override void Dispose(bool disposing)
        {
            if (disposing)
            {
                db.Dispose();
            }
            base.Dispose(disposing);
        }

        private bool ClientFundingExists(string userEmail, int fundingYear, int fundingMonth)
        {
            return db.ClientFundings.Count(e => e.UserEmail == userEmail && e.FundingYear==fundingYear && e.FundingMonth==fundingMonth) > 0;
        }
    }
}